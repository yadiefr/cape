@extends('layouts.admin')

@section('title', 'Storage Sync')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-sync-alt"></i>
                        Storage File Synchronization
                    </h3>
                </div>
                <div class="card-body">
                    @if($isHosting)
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Hosting Environment Detected</h5>
                            This tool helps synchronize uploaded files between your Laravel project and the public directory.
                            <br>
                            <strong>Source:</strong> /home/user/project_laravel/storage/app/public/<br>
                            <strong>Target:</strong> /home/user/public_html/storage/
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary">
                                        <i class="fas fa-file"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Files</span>
                                        <span class="info-box-number">{{ $syncStatus['total_files'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Synced Files</span>
                                        <span class="info-box-number">{{ $syncStatus['synced_files'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning">
                                        <i class="fas fa-exclamation"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Missing Files</span>
                                        <span class="info-box-number">{{ $syncStatus['missing_files'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon {{ ($syncStatus['sync_needed'] ?? false) ? 'bg-danger' : 'bg-success' }}">
                                        <i class="fas {{ ($syncStatus['sync_needed'] ?? false) ? 'fa-times' : 'fa-check' }}"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Status</span>
                                        <span class="info-box-number">{{ ($syncStatus['sync_needed'] ?? false) ? 'Needs Sync' : 'Up to Date' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="button" id="syncBtn" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sync-alt"></i>
                                    Sync Files Now
                                </button>
                                <button type="button" id="checkStatusBtn" class="btn btn-secondary btn-lg ml-2">
                                    <i class="fas fa-search"></i>
                                    Check Status
                                </button>
                            </div>
                        </div>
                        
                        <div id="syncProgress" class="mt-3" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 100%">
                                    Synchronizing files...
                                </div>
                            </div>
                        </div>
                        
                        <div id="syncResults" class="mt-3" style="display: none;">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Sync Results</h5>
                                </div>
                                <div class="card-body">
                                    <div id="syncResultsContent"></div>
                                </div>
                            </div>
                        </div>
                        
                    @else
                        <div class="alert alert-success">
                            <h5><i class="icon fas fa-check"></i> Localhost Environment</h5>
                            You are running on localhost. File synchronization is not needed as Laravel can directly access storage files.
                            <br>
                            <strong>Storage Path:</strong> {{ storage_path('app/public/') }}
                        </div>
                    @endif
                    
                    <div class="mt-4">
                        <h5>Instructions:</h5>
                        <ol>
                            <li><strong>Check Status:</strong> Click "Check Status" to see current sync status</li>
                            <li><strong>Sync Files:</strong> Click "Sync Files Now" to copy all uploaded files to public directory</li>
                            <li><strong>Automatic Sync:</strong> Files are also automatically synced when you upload new content</li>
                        </ol>
                        
                        <h5 class="mt-3">When to use this tool:</h5>
                        <ul>
                            <li>After uploading school logo or other settings images</li>
                            <li>When images are not showing on the website</li>
                            <li>After restoring a backup or transferring files</li>
                            <li>When you notice missing profile pictures or uploads</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Sync files
    $('#syncBtn').click(function() {
        var btn = $(this);
        var originalText = btn.html();
        
        btn.prop('disabled', true);
        btn.html('<i class="fas fa-spinner fa-spin"></i> Syncing...');
        $('#syncProgress').show();
        $('#syncResults').hide();
        
        $.ajax({
            url: '{{ route("admin.storage-sync.sync") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#syncProgress').hide();
                
                if (response.success) {
                    var html = '<div class="alert alert-success">';
                    html += '<h6>' + response.message + '</h6>';
                    
                    if (response.results && response.results.length > 0) {
                        html += '<table class="table table-sm mt-2">';
                        html += '<thead><tr><th>File</th><th>Key</th><th>Status</th><th>Message</th></tr></thead>';
                        html += '<tbody>';
                        
                        response.results.forEach(function(result) {
                            var statusClass = result.success ? 'text-success' : 'text-danger';
                            var statusIcon = result.success ? 'fa-check' : 'fa-times';
                            
                            html += '<tr>';
                            html += '<td>' + result.file + '</td>';
                            html += '<td>' + (result.key || '') + '</td>';
                            html += '<td><i class="fas ' + statusIcon + ' ' + statusClass + '"></i></td>';
                            html += '<td>' + result.message + '</td>';
                            html += '</tr>';
                        });
                        
                        html += '</tbody></table>';
                    }
                    
                    html += '</div>';
                    $('#syncResultsContent').html(html);
                } else {
                    $('#syncResultsContent').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
                
                $('#syncResults').show();
                
                // Refresh page after successful sync
                if (response.success) {
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                }
            },
            error: function(xhr) {
                $('#syncProgress').hide();
                var errorMessage = 'Sync failed';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                $('#syncResultsContent').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                $('#syncResults').show();
            },
            complete: function() {
                btn.prop('disabled', false);
                btn.html(originalText);
            }
        });
    });
    
    // Check status
    $('#checkStatusBtn').click(function() {
        location.reload();
    });
});
</script>

@endsection
