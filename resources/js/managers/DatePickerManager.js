class DatePickerManager {
    constructor() {
        console.log('DatePickerManager: Constructor initialized');
        this.instances = new Map();
        this.initialized = false;
        this.settings = null;
        this.dependencies = {
            scripts: [
                {
                    name: 'jQuery',
                    url: 'https://code.jquery.com/jquery-3.6.0.min.js',
                    check: () => typeof jQuery !== 'undefined'
                },
                {
                    name: 'persianDate',
                    url: 'https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js',
                    check: () => typeof persianDate !== 'undefined'
                },
                {
                    name: 'persianDatepicker',
                    url: 'https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js',
                    check: () => typeof jQuery !== 'undefined' && typeof jQuery.fn.persianDatepicker !== 'undefined'
                }
            ],
            styles: [
                {
                    url: 'https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css'
                }
            ]
        };
    
        // Single initialization chain
        this.loadSettings()
            .then(() => this.loadDependencies())
            .then(() => {
                this.initializeModalTriggers();
                this.initializeDefaultDatePicker();
            })
            .catch(error => {
                console.error('DatePickerManager initialization error:', error);
            });
    }

    initializeDefaultDatePicker() {
        // Find elements in the main page (non-modal context)
        const mainContext = document;
        const elements = this.findRequiredElements(mainContext);
        
        if (elements) {
            const instance = {
                context: mainContext,
                elements,
                currentDate: null,
                currentTimeSlot: null
            };
    
            this.instances.set('default', instance);
            this.setupEventListeners(instance);
            this.initializeDatePicker(instance);
            this.checkForExistingDate(instance);
            console.log('DatePickerManager: Default instance initialized');
        }
    }

    initializeModalTriggers() {
        const modalTriggers = document.querySelectorAll('.modal-trigger[data-modal-target*="bookingEditModal"]');
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', () => {
                const modalId = trigger.dataset.modalTarget;
                const modal = document.getElementById(modalId);
                
                // Wait for modal to be fully visible
                setTimeout(() => {
                    this.initializeForModal(modal);
                }, 100);
            });
        });
    }


    initializeForModal(modal) {
        if (!modal || this.instances.has(modal.id)) return;
        
        try {
            const instance = {
                context: modal,
                elements: this.findRequiredElements(modal),
                currentDate: null,
                currentTimeSlot: null
            };
    
            if (!instance.elements) {
                throw new Error('Required elements not found in modal');
            }
    
            // Set initial values if they exist
            const dateInput = instance.elements.dateInput;
            const timeSlotInput = instance.elements.timeSlotInput;
            
            if (dateInput.value) {
                instance.currentDate = dateInput.value;
            }
            
            if (timeSlotInput.value) {
                instance.currentTimeSlot = timeSlotInput.value;
            }
    
            this.instances.set(modal.id, instance);
            this.setupEventListeners(instance);
            this.initializeDatePicker(instance);
            
            // If we have an initial date, load the time slots
            if (instance.currentDate) {
                this.loadTimeSlots(instance.currentDate, instance);
            }
    
            console.log(`DatePickerManager: Modal instance ${modal.id} initialized with date:`, instance.currentDate);
        } catch (error) {
            console.error('Modal initialization error:', error);
            this.handleError(error);
        }
    }

    findRequiredElements(context) {
        const elements = {
            dateInput: context.querySelector('[data-date-input]'),
            timeSlotsContainer: context.querySelector('[data-time-slots-container]'),
            timeSlotsGrid: context.querySelector('[data-time-slots-grid]'),
            timeSlotInput: context.querySelector('[data-time-slot-input]')
        };
    
        // For modal context, we want all elements to be present
        if (context !== document) {
            const missingElements = Object.entries(elements)
                .filter(([key, element]) => !element)
                .map(([key]) => key);
    
            if (missingElements.length > 0) {
                console.error('Missing required elements:', missingElements.join(', '));
                return null;
            }
        }
    
        // For non-modal context, we only need dateInput to be present
        if (context === document && !elements.dateInput) {
            console.error('Date input element not found in main context');
            return null;
        }
    
        return elements;
    }

    setupEventListeners(instance) {
        const { context } = instance;

        context.addEventListener('dateSelected', (event) => {
            const { formatted } = event.detail;
            instance.currentDate = formatted;
            this.showTimeSlots(instance);
            this.loadTimeSlots(formatted, instance);
        });

        context.addEventListener('timeSlotSelected', (event) => {
            const { time } = event.detail;
            instance.currentTimeSlot = time;
            this.updateTimeSlotSelection(time, instance);
        });
    }

    async loadDependencies() {
        console.log('DatePickerManager: Loading dependencies...');
        
        this.dependencies.styles.forEach(style => this.loadStyle(style.url));
        
        for (const script of this.dependencies.scripts) {
            if (!script.check()) {
                await this.loadScript(script.url);
            }
        }

        const missingDeps = this.dependencies.scripts.filter(script => !script.check());
        if (missingDeps.length > 0) {
            throw new Error(`Failed to load dependencies: ${missingDeps.map(d => d.name).join(', ')}`);
        }
    }

    loadStyle(url) {
        if (!document.querySelector(`link[href="${url}"]`)) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = url;
            document.head.appendChild(link);
        }
    }

    loadScript(url) {
        return new Promise((resolve, reject) => {
            if (document.querySelector(`script[src="${url}"]`)) {
                resolve();
                return;
            }

            const script = document.createElement('script');
            script.src = url;
            script.async = true;
            script.onload = resolve;
            script.onerror = () => reject(new Error(`Failed to load script: ${url}`));
            document.head.appendChild(script);
        });
    }
    
    async loadSettings() {
        try {
            const response = await fetch('/dashboard/datepicker-settings', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error(`Server responded with status: ${response.status}`);
            }
            
            const data = await response.json();
            this.settings = data;
            console.log('DatePicker settings loaded:', this.settings);
        } catch (error) {
            console.error('Error loading settings:', error);
            throw error;
        }
    }

    initializeDatePicker(instance) {
        const { dateInput } = instance.elements;
        
        if (!dateInput) return;
    
        const options = {
            format: 'YYYY/MM/DD',
            initialValue: true,
            initialValueType: 'persian',
            persianDigit: true,
            observer: true,
            calendar: {
                persian: {
                    locale: 'fa'
                }
            },
            onSelect: (unix) => {
                this.handleDateSelection(unix, instance);
            },
            toolbox: {
                calendarSwitch: {
                    enabled: false
                }
            },
            navigator: {
                scroll: {
                    enabled: false
                },
                text: {
                    btnNextText: "بعد",
                    btnPrevText: "قبل"
                }
            },
            minDate: new persianDate().startOf('day'),
            timePicker: {
                enabled: false
            },
            checkDate: (unix) => {
                return this.isDateAvailable(unix);
            },
            onShow: () => {
                setTimeout(() => {
                    this.updateCalendarDisplay();
                }, 0);
            }
        };
    
        $(dateInput).persianDatepicker(options);
    }

    updateCalendarDisplay() {
        const dayElements = document.querySelectorAll('.datepicker-day-view td[data-unix]');
        dayElements.forEach(el => {
            const unix = parseInt(el.getAttribute('data-unix'));
            const date = new persianDate(unix);
            
            // بررسی روز جمعه براساس تنظیمات
            // در persianDate: شنبه=0، یکشنبه=1، ..., پنجشنبه=5، جمعه=7
            if (this.settings?.fridays_closed && date.day() === 7) { // جمعه = 7
                el.classList.add('disabled');
                el.setAttribute('disabled', 'disabled');
                el.classList.add('holiday');
                el.title = 'روز تعطیل';
            }
        });
    }

    isDateAvailable(unix) {
        const date = new persianDate(unix);
        const now = new persianDate();
        const currentHour = now.hour();
        const currentMinute = now.minute();
    
        // بررسی روز جمعه براساس تنظیمات
        // در persianDate: شنبه=0، یکشنبه=1، ..., پنجشنبه=5، جمعه=7
        if (this.settings?.fridays_closed && date.day() === 7) { // جمعه = 7
            return false;
        }
    
        // اگر تاریخ برای روزهای آینده است، مجاز است
        if (date.startOf('day').unix() > now.startOf('day').unix()) {
            return true;
        }
    
        // اگر تاریخ برای امروز است
        if (date.startOf('day').unix() === now.startOf('day').unix()) {
            return this.checkRemainingTimeSlots(currentHour, currentMinute);
        }
    
        return false;
    }

    async checkRemainingTimeSlots(currentHour, currentMinute) {
        try {
            const today = new persianDate().format('YYYY/MM/DD');
            const response = await fetch(`/dashboard/available-times?date=${today}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
    
            if (!response.ok) return false;
    
            const data = await response.json();
            const availableSlots = data.available || [];
    
            // بررسی اینکه آیا ساعت خالی بعد از ساعت فعلی وجود دارد
            return availableSlots.some(slot => {
                const [slotHour, slotMinute] = slot.split(':').map(Number);
                return slotHour > currentHour || (slotHour === currentHour && slotMinute > currentMinute);
            });
        } catch (error) {
            console.error('Error checking remaining time slots:', error);
            return false;
        }
    }

    handleDateSelection(unix, instance) {
        try {
            const date = new persianDate(unix);
            // فرمت کردن تاریخ به شمسی با اعداد فارسی
            const formattedDate = date.toLocale('fa').format('YYYY/MM/DD');
            console.log('Selected date:', formattedDate);
            
            instance.elements.dateInput.value = formattedDate;
            
            instance.context.dispatchEvent(new CustomEvent('dateSelected', {
                detail: {
                    unix,
                    formatted: formattedDate
                }
            }));
        } catch (error) {
            console.error('Error handling date selection:', error);
            this.handleError(error, instance);
        }
    }

    async loadTimeSlots(selectedDate, instance) {
        try {
            this.showLoadingState(instance);
    
            // تاریخ رو همونطور که هست (به فارسی) ارسال میکنیم
            const response = await fetch(`/dashboard/available-times?date=${selectedDate}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error(`Server responded with status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Time slots data:', data);
    
            if (data.message) {
                this.showMessage(data.message, 'info', instance);
                return;
            }
    
            const availableSlots = data.available || [];
            const bookedSlots = new Set(data.booked || []);
    
            this.renderTimeSlots({ available: availableSlots, booked: Array.from(bookedSlots) }, instance);
            
        } catch (error) {
            console.error('Error loading time slots:', error);
            this.handleError(error, instance);
        }
    }

    renderTimeSlots({ available, booked }, instance) {
        console.log('Rendering time slots:', { available, booked });
        const { elements } = instance;
    
        if (!elements.timeSlotsGrid) {
            console.error('Time slots grid element not found');
            return;
        }
    
        elements.timeSlotsGrid.innerHTML = '';
    
        if (!available || available.length === 0) {
            this.showNoTimeSlotsMessage(instance);
            return;
        }
    
        const currentSelection = elements.timeSlotInput?.value;
        const bookedSet = new Set(booked);
        const now = new persianDate();
        const currentHour = now.hour();
        const currentMinute = now.minute();
        const selectedDate = new persianDate(instance.currentDate);
        const isToday = selectedDate.startOf('day').unix() === now.startOf('day').unix();
        
        const allTimeSlots = [...available, ...booked].sort((a, b) => {
            const [hoursA, minutesA] = a.split(':').map(Number);
            const [hoursB, minutesB] = b.split(':').map(Number);
            return (hoursA * 60 + minutesA) - (hoursB * 60 + minutesB);
        });
    
        allTimeSlots.forEach(time => {
            const button = document.createElement('button');
            button.type = 'button';
            const [slotHour, slotMinute] = time.split(':').map(Number);
            const isBooked = bookedSet.has(time);
            const isSelected = time === currentSelection;
            const isPastTime = isToday && (slotHour < currentHour || (slotHour === currentHour && slotMinute <= currentMinute));
    
            button.className = `
                w-full px-3 py-2 text-sm rounded-lg transition-colors duration-200
                ${isSelected ? 'bg-blue-500 text-white' : 
                  isBooked || isPastTime ? 'bg-gray-200 text-gray-400 cursor-not-allowed opacity-50' : 
                  'bg-gray-100 text-gray-700 hover:bg-gray-200'}
            `.trim();
    
            button.textContent = time;
            button.dataset.time = time;
            
            if (isBooked) {
                button.disabled = true;
                button.title = 'این ساعت قبلاً رزرو شده است';
            } else if (isPastTime) {
                button.disabled = true;
                button.title = 'این ساعت گذشته است';
            } else {
                button.onclick = () => this.handleTimeSlotSelection(time, instance);
            }
            
            elements.timeSlotsGrid.appendChild(button);
        });
    }

    showLoadingState(instance) {
        const { elements } = instance;
        if (elements.timeSlotsGrid) {
            elements.timeSlotsGrid.innerHTML = `
                <div class="col-span-4 text-center py-4">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
                    <div class="mt-2">در حال بارگذاری ساعات مراجعه...</div>
                </div>
            `;
        }
    }

    showMessage(message, type = 'info', instance) {
        const { elements } = instance;
        if (elements.timeSlotsGrid) {
            const colors = {
                info: 'text-blue-500',
                error: 'text-red-500',
                warning: 'text-yellow-500'
            };
    
            elements.timeSlotsGrid.innerHTML = `
                <div class="col-span-4 text-center py-4 ${colors[type]}">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="${type === 'info' ? 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' : 
                              type === 'warning' ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' : 
                              'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'}" />
                    </svg>
                    <p class="mt-2">${message}</p>
                </div>
            `;
        }
    }

    showNoTimeSlotsMessage(instance) {
        const { elements } = instance;
        if (elements.timeSlotsGrid) {
            elements.timeSlotsGrid.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="flex flex-col items-center justify-center text-gray-500">
                        <svg class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" 
                                  stroke-linejoin="round" 
                                  stroke-width="1.5" 
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-lg font-medium">هیچ ساعت خالی برای این روز وجود ندارد</p>
                        <p class="text-sm mt-2">لطفاً روز دیگری را انتخاب کنید</p>
                    </div>
                </div>
            `;
        }
    }

    handleTimeSlotSelection(time, instance) {
        console.log('Time slot selected:', time);
        instance.context.dispatchEvent(new CustomEvent('timeSlotSelected', {
            detail: { time }
        }));
    }

    updateTimeSlotSelection(selectedTime, instance) {
        const { elements } = instance;
        if (elements.timeSlotInput) {
            elements.timeSlotInput.value = selectedTime;
        }
        
        const timeSlots = elements.timeSlotsGrid.querySelectorAll('button');
        timeSlots.forEach(slot => {
            if (slot.dataset.time === selectedTime) {
                slot.className = 'w-full px-3 py-2 text-sm rounded-lg transition-colors duration-200 bg-blue-500 text-white';
            } else if (!slot.disabled) {
                slot.className = 'w-full px-3 py-2 text-sm rounded-lg transition-colors duration-200 bg-gray-100 text-gray-700 hover:bg-gray-200';
            }
        });
    }

    showTimeSlots(instance) {
        const { elements } = instance;
        if (elements.timeSlotsContainer) {
            elements.timeSlotsContainer.classList.remove('hidden');
        }
    }

    checkForExistingDate(instance) {
        const { elements } = instance;
        const existingDate = elements.dateInput?.value;
        if (existingDate) {
            // اگر تاریخ از قبل وجود داره، اون رو به عنوان تاریخ فعلی ست میکنیم
            instance.currentDate = existingDate;
            this.showTimeSlots(instance);
            this.loadTimeSlots(existingDate, instance);
        }
    }

    handleError(error, instance) {
        console.error('DatePickerManager Error:', error);
        
        if (instance?.elements?.timeSlotsGrid) {
            instance.elements.timeSlotsGrid.innerHTML = `
                <div class="col-span-4 text-center py-4 text-red-500">
                    <p>خطا در بارگذاری ساعات مراجعه</p>
                    <p class="text-sm mt-2">${error.message}</p>
                </div>
            `;
        }
    }

    destroyInstance(modalId) {
        const instance = this.instances.get(modalId);
        if (instance) {
            try {
                if (instance.elements.dateInput && typeof jQuery !== 'undefined') {
                    $(instance.elements.dateInput).persianDatepicker('destroy');
                }
                this.instances.delete(modalId);
                console.log(`DatePickerManager: Instance ${modalId} destroyed`);
            } catch (error) {
                console.error(`Error destroying instance ${modalId}:`, error);
            }
        }
    }

    destroy() {
        try {
            this.instances.forEach((instance, modalId) => {
                this.destroyInstance(modalId);
            });
            this.instances.clear();
            this.initialized = false;
            console.log('DatePickerManager: All instances destroyed');
        } catch (error) {
            console.error('Error destroying DatePickerManager:', error);
        }
    }
}

export default DatePickerManager;