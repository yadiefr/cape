// SMK Modern - Enhanced JavaScript 2025

document.addEventListener('DOMContentLoaded', function() {
    // Preloader
    window.addEventListener('load', function() {
        const preloader = document.querySelector('.preloader');
        preloader.classList.add('fade-out');
        
        setTimeout(function() {
            preloader.style.display = 'none';
        }, 500);
    });
    
    // Initialize AOS Animation
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });
    
    // Mobile Menu Toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            mobileMenuBtn.classList.toggle('active');
        });
    }
    
    // Smooth Scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                navMenu.classList.remove('active');
                if (mobileMenuBtn) {
                    mobileMenuBtn.classList.remove('active');
                }
            }
        });
    });
    
    // Header Scroll Effect
    const header = document.querySelector('header');
    
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
    
    // Counter Animation
    const counterElements = document.querySelectorAll('.stat-counter');
    
    function animateCounter(el) {
        const target = parseInt(el.getAttribute('data-count'));
        const duration = 2000; // 2 seconds
        const step = Math.ceil(target / (duration / 16)); // 60fps
        let current = 0;
        
        const counterInterval = setInterval(() => {
            current += step;
            if (current >= target) {
                el.textContent = target;
                clearInterval(counterInterval);
            } else {
                el.textContent = current;
            }
        }, 16);
    }
    
    // Intersection Observer for counters
    if (counterElements.length > 0) {
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                    animateCounter(entry.target);
                    entry.target.classList.add('counted');
                }
            });
        }, { threshold: 0.5 });
        
        counterElements.forEach(counter => {
            counterObserver.observe(counter);
        });
    }
    
    // Back to Top Button
    const backToTopBtn = document.querySelector('.back-to-top');
    
    if (backToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('active');
            } else {
                backToTopBtn.classList.remove('active');
            }
        });
        
        backToTopBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Calendar Functionality
    const calendar = document.querySelector('.calendar');
    const monthName = document.querySelector('.month-name');
    const prevMonthBtn = document.querySelector('.prev-month');
    const nextMonthBtn = document.querySelector('.next-month');
    const calendarDays = document.querySelector('.calendar-days');
    
    if (calendar) {
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        
        // Sample event data
        const events = [
            { date: new Date(currentYear, currentMonth, 15), title: 'Penerimaan Rapor', time: '09:00 - 12:00', location: 'Aula Sekolah' },
            { date: new Date(currentYear, currentMonth, 20), title: 'Lomba Karya Ilmiah', time: '08:00 - 15:00', location: 'Lab Komputer' },
            { date: new Date(currentYear, currentMonth + 1, 5), title: 'Upacara Bendera', time: '07:30 - 08:30', location: 'Lapangan Sekolah' },
            { date: new Date(currentYear, currentMonth, 25), title: 'Kunjungan Industri', time: '08:00 - 17:00', location: 'PT. Techno Global' }
        ];
        
        function renderCalendar() {
            // Get first day of month
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            // Get number of days in month
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            // Get last day of prev month
            const prevMonthDays = new Date(currentYear, currentMonth, 0).getDate();
            
            // Update month name
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            monthName.textContent = `${monthNames[currentMonth]} ${currentYear}`;
            
            // Clear calendar days
            calendarDays.innerHTML = '';
            
            // Add day names
            const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            dayNames.forEach(day => {
                const dayNameEl = document.createElement('div');
                dayNameEl.classList.add('calendar-day-name');
                dayNameEl.textContent = day;
                calendarDays.appendChild(dayNameEl);
            });
            
            // Add prev month days
            for (let i = firstDay - 1; i >= 0; i--) {
                const dayEl = document.createElement('div');
                dayEl.classList.add('calendar-date', 'other-month');
                dayEl.textContent = prevMonthDays - i;
                calendarDays.appendChild(dayEl);
            }
            
            // Add current month days
            const today = new Date();
            for (let i = 1; i <= daysInMonth; i++) {
                const dayEl = document.createElement('div');
                dayEl.classList.add('calendar-date');
                dayEl.textContent = i;
                
                // Check if day is today
                if (currentYear === today.getFullYear() && currentMonth === today.getMonth() && i === today.getDate()) {
                    dayEl.classList.add('current');
                }
                
                // Check if day has events
                const hasEvent = events.some(event => 
                    event.date.getFullYear() === currentYear && 
                    event.date.getMonth() === currentMonth && 
                    event.date.getDate() === i
                );
                
                if (hasEvent) {
                    dayEl.classList.add('has-event');
                }
                
                calendarDays.appendChild(dayEl);
            }
            
            // Add next month days
            const totalDaysDisplayed = calendarDays.children.length;
            const daysNeeded = 42 - totalDaysDisplayed; // 6 rows * 7 days
            
            for (let i = 1; i <= daysNeeded; i++) {
                const dayEl = document.createElement('div');
                dayEl.classList.add('calendar-date', 'other-month');
                dayEl.textContent = i;
                calendarDays.appendChild(dayEl);
            }
        }
        
        // Render initial calendar
        renderCalendar();
        
        // Previous month button
        if (prevMonthBtn) {
            prevMonthBtn.addEventListener('click', () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar();
            });
        }
        
        // Next month button
        if (nextMonthBtn) {
            nextMonthBtn.addEventListener('click', () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar();
            });
        }
        
        // Render upcoming events
        const upcomingEventsContainer = document.querySelector('.upcoming-events');
        
        if (upcomingEventsContainer) {
            // Sort events by date
            const sortedEvents = [...events].sort((a, b) => a.date - b.date);
            
            // Filter future events or current day events
            const futureEvents = sortedEvents.filter(event => {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                return event.date >= today;
            }).slice(0, 3); // Get only 3 nearest events
            
            // Create event cards
            futureEvents.forEach(event => {
                const eventCard = document.createElement('div');
                eventCard.classList.add('event-card');
                
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                
                eventCard.innerHTML = `
                    <div class="event-date">
                        <div class="event-day">${event.date.getDate()}</div>
                        <div class="event-month">${monthNames[event.date.getMonth()]}</div>
                    </div>
                    <div class="event-content">
                        <h3 class="event-title">${event.title}</h3>
                        <div class="event-time">
                            <i class="fas fa-clock"></i> ${event.time}
                        </div>
                        <div class="event-location">
                            <i class="fas fa-map-marker-alt"></i> ${event.location}
                        </div>
                    </div>
                `;
                
                upcomingEventsContainer.appendChild(eventCard);
            });
        }
    }
    
    // Achievement Carousel
    const carouselTrack = document.querySelector('.carousel-track');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    
    if (carouselTrack) {
        let position = 0;
        const cards = carouselTrack.querySelectorAll('.achievement-card');
        const cardWidth = 320; // card width + gap
        const maxPosition = -(cards.length * cardWidth - carouselTrack.parentElement.offsetWidth);
        
        function updateCarouselPosition() {
            carouselTrack.style.transform = `translateX(${position}px)`;
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                position += cardWidth;
                if (position > 0) {
                    position = 0;
                }
                updateCarouselPosition();
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                position -= cardWidth;
                if (position < maxPosition) {
                    position = maxPosition;
                }
                updateCarouselPosition();
            });
        }
        
        // Auto slide
        let autoSlideInterval = setInterval(() => {
            position -= cardWidth;
            if (position < maxPosition) {
                position = 0;
            }
            updateCarouselPosition();
        }, 5000);
        
        // Pause auto slide on hover
        carouselTrack.addEventListener('mouseenter', () => {
            clearInterval(autoSlideInterval);
        });
        
        carouselTrack.addEventListener('mouseleave', () => {
            autoSlideInterval = setInterval(() => {
                position -= cardWidth;
                if (position < maxPosition) {
                    position = 0;
                }
                updateCarouselPosition();
            }, 5000);
        });
    }

    // Testimonial Slider
    initTestimonialSlider();

    // Calendar & Events
    initCalendar();

    // News Filter Functionality
    initNewsFilter();

    // Search Functionality
    initSearchBar();

    // Gallery Functionality
    initGallery();
});

/**
 * Initialize Testimonial Slider
 */
function initTestimonialSlider() {
    const testimonialTrack = document.querySelector('.testimonial-track');
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    const prevButton = document.querySelector('.testimonial-prev');
    const nextButton = document.querySelector('.testimonial-next');
    const dots = document.querySelectorAll('.testimonial-dot');
    
    if (!testimonialTrack || testimonialCards.length === 0) return;
    
    let currentIndex = 0;
    const maxIndex = testimonialCards.length - 1;
    const cardWidth = testimonialCards[0].offsetWidth;
    const cardMargin = parseInt(window.getComputedStyle(testimonialCards[0]).marginRight);
    
    // Update testimonial slider position
    function updateTestimonialPosition() {
        // Calculate how many cards to show based on screen width
        let visibleCards = 1;
        if (window.innerWidth >= 1024) {
            visibleCards = 3;
        } else if (window.innerWidth >= 768) {
            visibleCards = 2;
        }
        
        // Limit scrolling based on visible cards
        if (currentIndex > testimonialCards.length - visibleCards) {
            currentIndex = testimonialCards.length - visibleCards;
        }
        
        const offset = currentIndex * -(cardWidth + 16); // 16px is the gap
        testimonialTrack.style.transform = `translateX(${offset}px)`;
        
        // Update active dot
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }
    
    // Event listeners for buttons
    prevButton.addEventListener('click', () => {
        currentIndex = Math.max(0, currentIndex - 1);
        updateTestimonialPosition();
    });
    
    nextButton.addEventListener('click', () => {
        currentIndex = Math.min(maxIndex, currentIndex + 1);
        updateTestimonialPosition();
    });
    
    // Event listeners for dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentIndex = index;
            updateTestimonialPosition();
        });
    });
    
    // Auto-slide every 5 seconds
    setInterval(() => {
        currentIndex = (currentIndex === maxIndex) ? 0 : currentIndex + 1;
        updateTestimonialPosition();
    }, 5000);
    
    // Initial position
    updateTestimonialPosition();
    
    // Handle window resize
    window.addEventListener('resize', updateTestimonialPosition);
}

/**
 * Initialize Calendar
 */
function initCalendar() {
    const prevMonthBtn = document.querySelector('.prev-month');
    const nextMonthBtn = document.querySelector('.next-month');
    const monthNameEl = document.querySelector('.month-name');
    const calendarDates = document.querySelectorAll('.calendar-date');
    
    if (!prevMonthBtn || !nextMonthBtn || !monthNameEl) return;
    
    const months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();
    
    // Update calendar header
    function updateCalendarHeader() {
        monthNameEl.textContent = `${months[currentMonth]} ${currentYear}`;
    }
    
    // Handle month navigation
    prevMonthBtn.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        updateCalendarHeader();
        // In a real implementation, we would regenerate the calendar dates here
    });
    
    nextMonthBtn.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        updateCalendarHeader();
        // In a real implementation, we would regenerate the calendar dates here
    });
    
    // Add click event to calendar dates
    calendarDates.forEach(date => {
        date.addEventListener('click', () => {
            // Remove current class from all dates
            calendarDates.forEach(d => d.classList.remove('current'));
            // Add current class to clicked date
            date.classList.add('current');
            // In a real implementation, we would show events for this date
        });
    });
    
    // Initialize calendar
    updateCalendarHeader();
}

/**
 * Initialize News Filter
 */
function initNewsFilter() {
    const filterBtns = document.querySelectorAll('.news-filter-btn');
    const newsCards = document.querySelectorAll('.news-card');
    
    if (!filterBtns.length || !newsCards.length) return;
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            btn.classList.add('active');
            
            const filter = btn.getAttribute('data-filter');
            
            // Filter news cards (in a real implementation, this would filter based on categories)
            if (filter === 'all') {
                newsCards.forEach(card => {
                    card.style.display = 'flex';
                });
            } else {
                newsCards.forEach(card => {
                    const category = card.querySelector('.news-category').textContent.toLowerCase();
                    if (category === filter) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
        });
    });
    
    // Pagination functionality (in a real implementation this would be server-side)
    const paginationNumbers = document.querySelectorAll('.pagination-number');
    if (paginationNumbers.length) {
        paginationNumbers.forEach(num => {
            num.addEventListener('click', e => {
                e.preventDefault();
                // Remove active class from all numbers
                paginationNumbers.forEach(n => n.classList.remove('active'));
                // Add active class to clicked number
                num.classList.add('active');
                
                // In a real implementation, this would load the next page of news
                // For this demo, we'll just scroll back to the news section
                document.querySelector('#news').scrollIntoView({ behavior: 'smooth' });
            });
        });
    }
}

/**
 * Initialize Search Bar
 */
function initSearchBar() {
    const searchToggle = document.querySelector('.search-toggle-btn');
    const searchOverlay = document.querySelector('.search-overlay');
    const searchClose = document.querySelector('.search-close');
    const searchForm = document.querySelector('.search-form');
    const searchInput = document.querySelector('.search-input');
    
    if (!searchToggle || !searchOverlay || !searchClose) return;
    
    // Open search overlay
    searchToggle.addEventListener('click', () => {
        searchOverlay.classList.add('active');
        setTimeout(() => {
            searchInput.focus();
        }, 300);
        document.body.style.overflow = 'hidden';
    });
    
    // Close search overlay
    searchClose.addEventListener('click', () => {
        searchOverlay.classList.remove('active');
        document.body.style.overflow = '';
    });
    
    // Close search on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
            searchOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Handle search form submit
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const query = searchInput.value.trim();
            
            if (query) {
                // In a real implementation, this would redirect to a search results page
                // For now, let's just show an alert
                alert(`Mencari: ${query}`);
                
                // Close the search overlay
                searchOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
}

/**
 * Initialize Gallery Functionality
 */
function initGallery() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const galleryFilterBtns = document.querySelectorAll('.gallery-filter-btn');
    const galleryModal = document.querySelector('.gallery-modal');
    const galleryModalContent = document.querySelector('.gallery-modal-content');
    const galleryModalCaption = document.querySelector('.gallery-modal-caption');
    const galleryModalClose = document.querySelector('.gallery-modal-close');
    const galleryModalPrev = document.querySelector('.gallery-modal-prev');
    const galleryModalNext = document.querySelector('.gallery-modal-next');
    
    if (!galleryItems.length || !galleryModal) return;
    
    let currentImageIndex = 0;
    
    // Filter Gallery Items
    galleryFilterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.getAttribute('data-filter');
            
            // Update active button
            galleryFilterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            // Filter gallery items
            galleryItems.forEach(item => {
                const category = item.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Open Modal on Gallery Item Click
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', () => {
            const imgSrc = item.querySelector('img').getAttribute('src');
            const imgCaption = item.querySelector('.gallery-info h3').textContent;
            
            openModal(imgSrc, imgCaption, index);
        });
    });
    
    // Close Modal on Close Button Click
    galleryModalClose.addEventListener('click', closeModal);
    
    // Close Modal on Outside Click
    galleryModal.addEventListener('click', (e) => {
        if (e.target === galleryModal) {
            closeModal();
        }
    });
    
    // Close Modal on Escape Key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && galleryModal.classList.contains('active')) {
            closeModal();
        }
        
        // Navigate with arrow keys
        if (galleryModal.classList.contains('active')) {
            if (e.key === 'ArrowLeft') {
                navigateGallery('prev');
            } else if (e.key === 'ArrowRight') {
                navigateGallery('next');
            }
        }
    });
    
    // Modal Navigation
    galleryModalPrev.addEventListener('click', () => navigateGallery('prev'));
    galleryModalNext.addEventListener('click', () => navigateGallery('next'));
    
    // Open Gallery Modal
    function openModal(src, caption, index) {
        galleryModalContent.setAttribute('src', src);
        galleryModalCaption.textContent = caption;
        currentImageIndex = index;
        
        galleryModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // Close Gallery Modal
    function closeModal() {
        galleryModal.classList.remove('active');
        document.body.style.overflow = '';
        
        // Clear content after animation completes
        setTimeout(() => {
            galleryModalContent.setAttribute('src', '');
            galleryModalCaption.textContent = '';
        }, 300);
    }
    
    // Navigate Gallery Modal
    function navigateGallery(direction) {
        const visibleItems = Array.from(galleryItems).filter(item => item.style.display !== 'none');
        
        if (visibleItems.length <= 1) return;
        
        let newIndex;
        
        if (direction === 'prev') {
            newIndex = (currentImageIndex - 1 + visibleItems.length) % visibleItems.length;
        } else {
            newIndex = (currentImageIndex + 1) % visibleItems.length;
        }
        
        const newItem = visibleItems[newIndex];
        const imgSrc = newItem.querySelector('img').getAttribute('src');
        const imgCaption = newItem.querySelector('.gallery-info h3').textContent;
        
        galleryModalContent.style.opacity = '0';
        galleryModalContent.style.transform = 'scale(0.9)';
        galleryModalCaption.style.opacity = '0';
        
        setTimeout(() => {
            galleryModalContent.setAttribute('src', imgSrc);
            galleryModalCaption.textContent = imgCaption;
            currentImageIndex = newIndex;
            
            galleryModalContent.style.opacity = '1';
            galleryModalContent.style.transform = 'scale(1)';
            galleryModalCaption.style.opacity = '1';
        }, 200);
    }
}
