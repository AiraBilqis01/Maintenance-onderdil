@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .card-dashboard {
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
    
    .card-dashboard:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    
    .card-dashboard .card-header {
        padding: 1.5rem 2rem;
        border: none;
        font-weight: 700;
        font-size: 1.2rem;
        letter-spacing: 0.5px;
    }
    
    .card-dashboard .card-body {
        padding: 2.5rem 2rem;
        background: white;
        text-align: center;
    }
    
    .card-dashboard .icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
        transition: all 0.3s ease;
    }
    
    .card-dashboard:hover .icon-wrapper {
        transform: scale(1.1) rotate(-5deg);
    }
    
    .card-dashboard .number {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        line-height: 1;
    }
    
    .card-dashboard .label {
        color: #6c757d;
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }
    
    .btn-view {
        padding: 0.8rem 2.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-view:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    
    .card-tenaga .card-header {
        background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);
        color: white;
    }
    
    .card-tenaga .icon-wrapper {
        background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);
        color: white;
    }
    
    .card-tenaga .number {
        color: #284F57;
    }
    
    .card-tenaga .btn-view {
        background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);
        color: white;
    }
    
    .card-pemeliharaan .card-header {
        background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);
        color: white;
    }
    
    .card-pemeliharaan .icon-wrapper {
        background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);
        color: white;
    }
    
    .card-pemeliharaan .number {
        color: #284F57;
    }
    
    .card-pemeliharaan .btn-view {
        background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);
        color: white;
    }
</style>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card-dashboard card-tenaga">
            <div class="card-header">
                <i class="fas fa-users me-2"></i> Data Tenaga Kerja
            </div>
            <div class="card-body">
                <div class="icon-wrapper">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="number">25</div>
                <div class="label">Total Tenaga Kerja</div>
                <a href="#" class="btn-view">
                    <i class="fas fa-eye me-2"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card-dashboard card-pemeliharaan">
            <div class="card-header">
                <i class="fas fa-clipboard-list me-2"></i> Laporan Pemeliharaan
            </div>
            <div class="card-body">
                <div class="icon-wrapper">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="number">42</div>
                <div class="label">Total Laporan</div>
                <a href="#" class="btn-view">
                    <i class="fas fa-eye me-2"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>
@endsection