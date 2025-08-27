// Welcome page JavaScript functions
document.addEventListener('DOMContentLoaded', function() {
    // Start chat widget functionality
    const startChatBtn = document.getElementById('start-chat');
    if (startChatBtn) {
        startChatBtn.addEventListener('click', function() {
            window.open('https://wa.me/6281234567890?text=Halo%20saya%20ingin%20bertanya%20tentang%20SMK%20PGRI%20Cikamepek', '_blank');
        });
    }
    
    // Pastikan gambar hero tidak memiliki filter
    const heroImages = document.querySelectorAll('.hero-image img, .hero img');
    heroImages.forEach(function(img) {
        img.style.filter = 'none';
        img.style.webkitFilter = 'none';
        img.style.backdropFilter = 'none';
        img.style.webkitBackdropFilter = 'none';
        img.style.mixBlendMode = 'normal';
        img.style.opacity = '1';
        img.style.background = 'transparent';
        img.style.boxShadow = 'none';
    });
    
    // Hapus filter dari parent containers juga
    const heroContainers = document.querySelectorAll('.hero, .hero-image, section.hero');
    heroContainers.forEach(function(container) {
        container.style.filter = 'none';
        container.style.webkitFilter = 'none';
        container.style.backdropFilter = 'none';
        container.style.webkitBackdropFilter = 'none';
        container.style.mixBlendMode = 'normal';
    });
});

// Simple gallery modal functions
function openSimpleGalleryModal(galleryId, title, description) {
    document.getElementById('simpleGalleryTitle').textContent = title;
    document.getElementById('simpleGalleryDescription').textContent = description;
    
    // Show loading
    var photosContainer = document.getElementById('simpleGalleryPhotos');
    photosContainer.innerHTML = '<div style="text-align: center; padding: 20px;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #666;"></i><br><span style="color: #666;">Memuat foto...</span></div>';
    
    // Show modal
    document.getElementById('simpleGalleryModal').classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Fetch photos
    fetch('/api/galeri/' + galleryId + '/photos')
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            var html = '';
            if (data.success && data.photos && data.photos.length > 0) {
                data.photos.forEach(function(photo) {
                    html += '<div class="simple-photo-item">';
                    html += '<img src="' + photo.url + '" alt="' + photo.caption + '" onclick="viewImageFullscreen(\'' + photo.url + '\', \'' + photo.caption + '\')" style="cursor: pointer;">';
                    html += '</div>';
                });
            } else {
                html = '<div style="text-align: center; color: #666; padding: 40px;"><i class="fas fa-images" style="font-size: 3rem; margin-bottom: 10px;"></i><br>Tidak ada foto dalam galeri ini</div>';
            }
            photosContainer.innerHTML = html;
        })
        .catch(function(error) {
            photosContainer.innerHTML = '<div style="text-align: center; color: #dc3545; padding: 40px;"><i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 10px;"></i><br>Gagal memuat foto galeri<br><small>' + error.message + '</small></div>';
        });
}

function closeSimpleGalleryModal() {
    document.getElementById('simpleGalleryModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

// Gallery functionality
let currentGalleryPhotos = [];
let currentPhotoIndex = 0;

// Gallery Filter Function
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.gallery-filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter gallery items
            galleryItems.forEach(item => {
                const category = item.getAttribute('data-category');
                
                if (filter === 'all' || category === filter) {
                    item.style.display = 'block';
                    item.style.animation = 'fadeIn 0.5s ease-in';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Close modal when clicking outside
    var modal = document.getElementById('simpleGalleryModal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeSimpleGalleryModal();
            }
        });
    }
});

// Open gallery detail modal
function openGalleryModal(galleryId, title, description) {
    try {
        const galleryDetailTitle = document.getElementById('galleryDetailTitle');
        const galleryDetailDescription = document.getElementById('galleryDetailDescription');
        const photosContainer = document.getElementById('galleryDetailPhotos');
        const galleryDetailModal = document.getElementById('galleryDetailModal');
        
        if (!galleryDetailTitle || !galleryDetailDescription || !photosContainer || !galleryDetailModal) {
            console.error('Gallery modal elements not found');
            return;
        }
        
        galleryDetailTitle.textContent = title;
        galleryDetailDescription.textContent = description;
        
        // Show loading state
        photosContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-gray-500"></i><br><span class="text-gray-500">Memuat foto...</span></div>';
        
        // Show modal first
        galleryDetailModal.classList.add('show');
        
        // Disable body scroll
        document.body.style.overflow = 'hidden';
        
        // Fetch gallery photos
        fetch(`/api/galeri/${galleryId}/photos`)
            .then(response => response.json())
            .then(data => {
                currentGalleryPhotos = data.photos || [];
                let html = '';
                
                if (data.success && currentGalleryPhotos.length > 0) {
                    currentGalleryPhotos.forEach((photo, index) => {
                        html += `
                            <div class="gallery-detail-photo" onclick="openImageZoom(${index})">
                                <img src="${photo.url}" alt="${photo.caption}" loading="lazy">
                                <div class="photo-overlay">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="text-center text-gray-500 py-8"><i class="fas fa-images text-4xl mb-3"></i><br>Tidak ada foto dalam galeri ini</div>';
                }
                
                photosContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching gallery photos:', error);
                photosContainer.innerHTML = '<div class="text-center text-red-500 py-8"><i class="fas fa-exclamation-triangle text-4xl mb-3"></i><br>Gagal memuat foto galeri</div>';
            });
    } catch (error) {
        console.error('Error opening gallery modal:', error);
    }
}

// Close gallery detail modal
function closeGalleryModal() {
    try {
        const modal = document.getElementById('galleryDetailModal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        } else {
            console.error('Gallery detail modal not found');
        }
    } catch (error) {
        console.error('Error closing gallery modal:', error);
    }
}

// Open image zoom modal
function openImageZoom(photoIndex) {
    try {
        currentPhotoIndex = photoIndex;
        const photo = currentGalleryPhotos[photoIndex];
        const zoomedImage = document.getElementById('zoomedImage');
        const imageCaption = document.getElementById('imageCaption');
        const imageZoomModal = document.getElementById('imageZoomModal');
        
        if (zoomedImage && imageCaption && imageZoomModal) {
            zoomedImage.src = photo.url;
            zoomedImage.alt = photo.caption;
            imageCaption.textContent = photo.caption;
            imageZoomModal.classList.add('show');
        } else {
            console.error('Image zoom modal elements not found');
        }
    } catch (error) {
        console.error('Error opening image zoom:', error);
    }
}

// Close image zoom modal
function closeImageZoom() {
    try {
        const imageZoomModal = document.getElementById('imageZoomModal');
        if (imageZoomModal) {
            imageZoomModal.classList.remove('show');
        } else {
            console.error('Image zoom modal not found');
        }
    } catch (error) {
        console.error('Error closing image zoom:', error);
    }
}

// Navigate to previous image
function prevImage() {
    currentPhotoIndex = (currentPhotoIndex - 1 + currentGalleryPhotos.length) % currentGalleryPhotos.length;
    openImageZoom(currentPhotoIndex);
}

// Navigate to next image
function nextImage() {
    currentPhotoIndex = (currentPhotoIndex + 1) % currentGalleryPhotos.length;
    openImageZoom(currentPhotoIndex);
}

// Close modals when clicking outside
function handleModalClick(event) {
    if (event.target.id === 'galleryDetailModal') {
        closeGalleryModal();
    }
}

function handleZoomModalClick(event) {
    if (event.target.id === 'imageZoomModal') {
        closeImageZoom();
    }
}

// Handle escape key and navigation
document.addEventListener('keydown', function(event) {
    const galleryDetailModal = document.getElementById('galleryDetailModal');
    const imageZoomModal = document.getElementById('imageZoomModal');
    
    const detailModalOpen = galleryDetailModal && galleryDetailModal.classList.contains('show');
    const zoomModalOpen = imageZoomModal && imageZoomModal.classList.contains('show');
    
    switch(event.key) {
        case 'Escape':
            if (zoomModalOpen) {
                closeImageZoom();
            } else if (detailModalOpen) {
                closeGalleryModal();
            }
            break;
        case 'ArrowLeft':
            if (zoomModalOpen && currentGalleryPhotos.length > 1) {
                prevImage();
            }
            break;
        case 'ArrowRight':
            if (zoomModalOpen && currentGalleryPhotos.length > 1) {
                nextImage();
            }
            break;
    }
});

// Error handling for Cloudflare Insights
window.addEventListener('error', function(event) {
    if (event.filename && (event.filename.includes('beacon.min.js') || event.filename.includes('cloudflareinsights'))) {
        event.preventDefault();
        return false;
    }
});

// Handle network errors
window.addEventListener('unhandledrejection', function(event) {
    if (event.reason && event.reason.toString().includes('beacon.min.js')) {
        event.preventDefault();
    }
});

// Counter Animation for Statistics
function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200; // The lower the slower

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-count');
            const count = +counter.innerText;
            const inc = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + inc);
                setTimeout(updateCount, 1);
            } else {
                counter.innerText = target.toLocaleString();
            }
        };
        updateCount();
    });
}

// Trigger counter animation when statistics section is in view
const observerOptions = {
    threshold: 0.5,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateCounters();
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

const statsSection = document.querySelector('.statistics');
if (statsSection) {
    observer.observe(statsSection);
}

// Enhanced smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add hover effects to cards
document.querySelectorAll('.feature-card, .program-card, .facility-card, .testimonial-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px)';
    });

    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// Parallax effect for hero section
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const hero = document.querySelector('.hero');
    if (hero) {
        hero.style.transform = `translateY(${scrolled * 0.5}px)`;
    }
});

// Add loading animation to CTA buttons
document.querySelectorAll('.hero-cta-primary, .cta-button').forEach(button => {
    button.addEventListener('click', function(e) {
        if (!this.classList.contains('loading')) {
            this.classList.add('loading');
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            
            // Remove loading state after 3 seconds (for demo)
            setTimeout(() => {
                this.classList.remove('loading');
                this.innerHTML = this.dataset.originalText || 'Daftar Sekarang';
            }, 3000);
        }
    });
});

// Responsive adjustments based on screen size
function adjustForScreenSize() {
    const screenWidth = window.innerWidth;
    const isMobile = screenWidth < 768;
    const isTablet = screenWidth >= 768 && screenWidth < 992;
    const isDesktop = screenWidth >= 992;

    // Adjust AOS animations for mobile
    if (isMobile) {
        // Reduce animation duration for mobile
        document.querySelectorAll('[data-aos]').forEach(element => {
            element.setAttribute('data-aos-delay', '0');
        });
    }

    // Adjust hero stats layout
    const heroStats = document.querySelector('.hero-stats');
    if (heroStats) {
        if (isMobile) {
            heroStats.style.justifyContent = 'space-between';
        } else {
            heroStats.style.justifyContent = 'flex-start';
        }
    }

    // Adjust WhatsApp button position
    const whatsappButton = document.querySelector('.whatsapp-float');
    if (whatsappButton) {
        if (isMobile) {
            whatsappButton.style.width = '50px';
            whatsappButton.style.height = '50px';
            whatsappButton.style.fontSize = '1.2rem';
        } else {
            whatsappButton.style.width = '60px';
            whatsappButton.style.height = '60px';
            whatsappButton.style.fontSize = '1.5rem';
        }
    }
}

// Run on load and resize
adjustForScreenSize();
window.addEventListener('resize', debounce(adjustForScreenSize, 250));

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Optimize images loading for mobile
if (window.innerWidth < 768) {
    // Lazy load images more aggressively on mobile
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.loading = 'lazy';
    });
}

// Touch-friendly enhancements for mobile
if ('ontouchstart' in window) {
    document.body.classList.add('touch-device');

    // Add touch feedback to cards
    document.querySelectorAll('.testimonial-card, .facility-card, .info-card').forEach(card => {
        card.addEventListener('touchstart', function() {
            this.style.transform = 'translateY(-5px)';
        });

        card.addEventListener('touchend', function() {
            setTimeout(() => {
                this.style.transform = 'translateY(0)';
            }, 150);
        });
    });
}
