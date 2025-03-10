window.DateTimeManager = {
    initialized: false,

    initialize() {
        if (this.initialized) return;

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

    initDatepicker(element, bookingId) {
        const self = this;
        jQuery(element).persianDatepicker({
            initialValue: true,
            initialValueType: 'persian',
            format: 'YYYY/MM/DD',
            autoClose: true,
            maxDate: new persianDate().add('month', 3).valueOf(),
            minDate: new persianDate(),
            onSelect: function(unix) {
                const date = new persianDate(unix);
                const formattedDate = `${date.year()}/${date.month()}/${date.date()}`;
                self.handleDateSelection(formattedDate, bookingId);
                
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
        this.loadTimeSlots(selectedDate, '', bookingId); // ارسال empty string به جای currentTimeSlot
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