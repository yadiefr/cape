@if(config('third-party.cloudflare.web_analytics.enabled'))
<!-- Cloudflare Web Analytics -->
<script>
    (function() {
        'use strict';
        
        // Only load if not suppressed and in production
        if (!{{ config('third-party.cloudflare.web_analytics.suppress_errors') ? 'true' : 'false' }} || 
            '{{ config("app.env") }}' === 'production') {
            
            // Cloudflare Analytics will be injected automatically by Cloudflare
            // This script provides fallback handling
            
            // Check if Cloudflare Analytics loaded after 3 seconds
            setTimeout(function() {
                if (!window.__cfRum || !window.__cfRum.loaded) {
                    console.info('Cloudflare Analytics not loaded - possibly blocked by ad blocker');
                }
            }, 3000);
        }
        
        // Provide dummy analytics functions as fallback
        window.__cfRum = window.__cfRum || {
            loaded: false,
            push: function() {
                console.debug('Cloudflare Analytics blocked - using fallback');
            }
        };
    })();
</script>
@endif
