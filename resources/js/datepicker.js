import jQuery from 'jquery';

// Load persian-datepicker from CDN
const loadPersianDatepicker = async () => {
    // Load persian-date
    const persianDateScript = document.createElement('script');
    persianDateScript.src = 'https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js';
    document.head.appendChild(persianDateScript);

    // Load persian-datepicker CSS
    const persianDatepickerCSS = document.createElement('link');
    persianDatepickerCSS.rel = 'stylesheet';
    persianDatepickerCSS.href = 'https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css';
    document.head.appendChild(persianDatepickerCSS);

    // Load persian-datepicker
    const persianDatepickerScript = document.createElement('script');
    persianDatepickerScript.src = 'https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js';
    document.head.appendChild(persianDatepickerScript);

    // Wait for scripts to load
    return new Promise((resolve) => {
        persianDatepickerScript.onload = () => {
            // Configure persian-date globally
            if (window.persianDate) {
                window.persianDate.toLeapYearMode('algorithmic');
                window.persianDate.toLocale('fa');
                window.persianDate.toCalendar('persian');
            }
            resolve();
        };
    });
};

window.$ = window.jQuery = jQuery;

window.DateTimeManager = {
    initialized: false,

    async initialize() {
        if (this.initialized) return;

        // Load persian-datepicker dependencies
        await loadPersianDatepicker();
        
        // Test current date conversion
        this.testCurrentDate();

        // Make sure jQuery and persian-datepicker are loaded
        if (typeof jQuery().persianDatepicker === 'undefined') {
            console.error('Persian Datepicker is not loaded properly');
            return;
        }

        const newBookingDatepicker = document.querySelector('#date:not([id*="_"])');
        if (newBookingDatepicker) {
            this.initDatepicker(newBookingDatepicker, '');
        }

        const editModals = document.querySelectorAll('[id^="bookingEditModal-"]');
        editModals.forEach(modal => {
            const bookingId = modal.id.split('-')[1];
            const datepicker = modal.querySelector(`#date_${bookingId}`);
            if (datepicker) {
                this.initDatepicker(datepicker, bookingId);
            }
        });

        this.initialized = true;
    },

    testCurrentDate() {
        if (window.persianDate) {
            const now = new persianDate(new Date()).toLeapYearMode('algorithmic');
            console.log('Current Date Test:');
            console.log('Date:', now.format('YYYY/MM/DD'));
            console.log('Full Format:', now.format('dddd DD MMMM YYYY'));
            console.log('Time:', now.format('HH:mm:ss'));
            return now;
        }
    },

    initDatepicker(element, bookingId) {
        const today = new persianDate(new Date()).toLeapYearMode('algorithmic');
        
        jQuery(element).persianDatepicker({
            initialValue: true,
            initialValueType: 'persian',
            format: 'YYYY/MM/DD',
            autoClose: true,
            maxDate: today.add('month', 3).valueOf(),
            minDate: today,
            persianDigit: false,
            calendarType: 'persian',
            calendar: {
                persian: {
                    locale: 'fa',
                    leapYearMode: 'algorithmic',
                    epoch: 1348
                }
            },
            onSelect: (unix) => {
                const date = new persianDate(unix).toLeapYearMode('algorithmic');
                const formattedDate = `${date.year()}/${String(date.month()).padStart(2, '0')}/${String(date.date()).padStart(2, '0')}`;
                this.handleDateSelection(formattedDate, bookingId);
                
                const containerId = bookingId ? `time-slots-container-${bookingId}` : 'time-slots-container';
                const container = document.getElementById(containerId);
                if (container) container.classList.remove('hidden');
            }
        });

        if (element.value) {
            this.handleDateSelection(element.value, bookingId);
        }
    },

    handleDateSelection(selectedDate, bookingId) {
        // ریست کردن time slot انتخاب شده
        const timeSlotInput = document.getElementById(bookingId ? `time_slot_${bookingId}` : 'time_slot');
        if (timeSlotInput) {
            timeSlotInput.value = ''; // پاک کردن مقدار قبلی
        }
    
        // نمایش کانتینر time slots
        const containerId = bookingId ? `time-slots-container-${bookingId}` : 'time-slots-container';
        const container = document.getElementById(containerId);
        if (container) {
            container.classList.remove('hidden');
        }
    
        // لود کردن time slots جدید
        this.loadTimeSlots(selectedDate, '', bookingId); // ارسال empty string به جای currentTimeSlo
    },

    loadTimeSlots(selectedDate, currentTimeSlot, bookingId) {
        const gridId = bookingId ? `time-slots-grid-${bookingId}` : 'time-slots-grid';
        const container = document.getElementById(gridId);
        
        if (container) {
            container.className = 'grid grid-cols-4 gap-4';
            
            fetch(`/dashboard/available-times?date=${selectedDate}${bookingId ? '&booking_id=' + bookingId : ''}`)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = '';
                    const bookedTimes = data.booked || [];
                    
                    const totalItems = data.all.length;
                    const lastRowItems = totalItems % 4;
                    
                    data.all.forEach((time, index) => {
                        const isBooked = bookedTimes.includes(time);
                        // فقط زمانی currentTimeSlot رو پاس میدیم که در حالت edit هستیم
                        const effectiveCurrentTimeSlot = bookingId ? currentTimeSlot : '';
                        const slot = this.createTimeSlot(time, isBooked, effectiveCurrentTimeSlot, bookingId);
                        
                        if (lastRowItems > 0 && index >= totalItems - lastRowItems) {
                            const colSpan = 12 / lastRowItems;
                            slot.className = `time-slot transform transition-all duration-300 col-span-${colSpan}`;
                        }
                        
                        container.appendChild(slot);
                    });
                });
        }
    },

    createTimeSlot(time, isBooked, currentValue, bookingId) {
        const div = document.createElement('div');
        div.className = 'time-slot transform transition-all duration-300';
    
        const input = document.createElement('input');
        const inputId = `time-${time}-${bookingId}`;
        input.type = 'radio';
        input.name = bookingId ? `time_slot_${bookingId}` : 'time_slot';
        input.id = inputId;
        input.value = time;
        input.className = 'hidden';
        input.disabled = isBooked;
        input.checked = time === currentValue;
    
        const label = document.createElement('label');
        label.htmlFor = inputId;
        
        // Highlight the user's previous booking time
        const isPreviousBooking = time === currentValue;
        label.className = this.getTimeSlotClasses(isBooked, input.checked, isPreviousBooking);
        label.textContent = time;
        
        if (isPreviousBooking) {
            label.classList = 'block w-full text-center px-3 py-2 rounded-lg text-sm transition-all duration-300 bg-red-500 text-white cursor-not-allowed';
            label.textContent = time;
        }        
    
        if (!isBooked) {
            label.addEventListener('click', () => {
                const previousBooking = currentValue;
                const baseClasses = 'block w-full text-center px-3 py-2 rounded-lg text-sm transition-all duration-300';
                
                document.querySelectorAll('.time-slot label').forEach(l => {
                    const timeText = l.textContent.trim();
                    const isThisBooked = l.classList.contains('cursor-not-allowed');
                    const isThisPreviousBooking = timeText === previousBooking;
                    
                    if (isThisPreviousBooking) {
                        l.className = `${baseClasses} bg-gray-100 border-2 border-red-500 text-red-700`;
                    } else if (timeText === time) {
                        l.className = `${baseClasses} bg-blue-100 border-2 border-blue-500 text-blue-700 shadow-md`;
                    } else if (!isThisBooked) {
                        l.className = `${baseClasses} bg-white border border-gray-200 text-gray-700 cursor-pointer hover:bg-blue-50 hover:border-blue-300`;
                    }
                });
                
                const timeSlotInput = document.getElementById(bookingId ? `time_slot_${bookingId}` : 'time_slot');
                if (timeSlotInput) {
                    timeSlotInput.value = time;
                }
            });
        }                               
    
        div.appendChild(input);
        div.appendChild(label);
        return div;
    },
    
    getTimeSlotClasses(isBooked, isSelected) {
        const baseClasses = 'block w-full text-center px-3 py-2 rounded-lg text-sm transition-all duration-300';
    
        if (isBooked) {
            return `${baseClasses} bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed`;
        }
    
        if (isSelected) {
            return `${baseClasses} bg-blue-100 border-2 border-blue-500 text-blue-700 shadow-md`;
        }
    
        return `${baseClasses} bg-white border border-gray-200 text-gray-700 cursor-pointer hover:bg-blue-50 hover:border-blue-300`;
    } 
};

if (!window.dateTimeManagerInitialized) {
    window.dateTimeManagerInitialized = true;
    document.addEventListener('DOMContentLoaded', () => {
        window.DateTimeManager.initialize();
    });
}
