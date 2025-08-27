@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">Notifikasi</h6>
                        </div>
                        <div class="col text-end">
                            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Tandai Semua Dibaca
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($notifications as $notification)
                        <div class="notification-item p-3 border-bottom {{ $notification->is_read ? 'bg-light' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 {{ $notification->is_read ? 'text-muted' : '' }}">
                                        {{ $notification->message }}
                                    </p>
                                    <small class="text-muted">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="d-flex">
                                    @if(!$notification->is_read)
                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" 
                                              method="POST" 
                                              class="me-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                Tandai Dibaca
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" 
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada notifikasi</p>
                        </div>
                    @endforelse

                    @if($notifications->hasPages())
                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 