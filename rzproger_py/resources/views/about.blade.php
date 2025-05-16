@extends('layouts.app')

@section('title', 'О нас')

@section('hero')
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4">О Люкс Отеле</h1>
        <p class="lead">Ваш премиальный поставщик жилья с 2010 года</p>
    </div>
</div>
@endsection

@section('content')
<div class="row mb-5">
    <div class="col-md-6">
        <h2>Наша история</h2>
        <p>Люкс Отель был основан в 2010 году с простой, но амбициозной целью: предоставить гостям непревзойденный опыт проживания в роскошном отеле по разумным ценам. То, что начиналось как небольшой бутик-отель, превратилось в известное имя в индустрии гостеприимства.</p>
        <p>Наш путь определялся стремлением к совершенству, вниманием к деталям и искренней страстью к гостеприимству. За эти годы мы усовершенствовали наши услуги и расширили наши предложения, но наши основные ценности остались неизменными.</p>
        <p>Сегодня Люкс Отель является свидетельством нашей преданности созданию запоминающегося отдыха для наших гостей, сочетая современные удобства с неподвластной времени элегантностью.</p>
    </div>
    <div class="col-md-6">
        <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="img-fluid rounded" alt="Hotel image">
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-12 text-center">
        <h2>Наши ценности</h2>
        <p class="lead mb-5">Эти основные принципы определяют всё, что мы делаем в Люкс Отеле</p>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-star text-primary mb-3" style="font-size: 3rem;"></i>
                <h4 class="card-title">Совершенство</h4>
                <p class="card-text">Мы стремимся к совершенству во всех аспектах нашего сервиса, от чистоты номеров до взаимодействия с клиентами. Наши высокие стандарты гарантируют, что гости получат только лучшее.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-heart text-primary mb-3" style="font-size: 3rem;"></i>
                <h4 class="card-title">Гостеприимство</h4>
                <p class="card-text">Настоящее гостеприимство означает создание теплой, приветливой атмосферы, где гости чувствуют себя ценными и окруженными заботой. Наш персонал стремится сделать ваше пребывание комфортным и запоминающимся.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-thumbs-up text-primary mb-3" style="font-size: 3rem;"></i>
                <h4 class="card-title">Честность</h4>
                <p class="card-text">Мы верим в честную, прозрачную деловую практику. Наши цены прозрачны, наши обещания выполняются, и мы всегда ставим потребности наших гостей на первое место.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-12 text-center mb-4">
        <h2>Познакомьтесь с нашей командой</h2>
        <p class="lead">Преданные профессионалы, которые делают Люкс Отель особенным</p>
    </div>

    <div class="col-md-3">
        <div class="card text-center mb-4">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="card-img-top" alt="Член команды">
            <div class="card-body">
                <h5 class="card-title">Азамат Асанов</h5>
                <p class="card-subtitle text-muted mb-2">Генеральный менеджер</p>
                <p class="card-text">Имея более 15 лет опыта в индустрии гостеприимства, Азамат обеспечивает бесперебойную работу каждого аспекта Люкс Отеля.</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center mb-4">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" class="card-img-top" alt="Член команды">
            <div class="card-body">
                <h5 class="card-title">Айгуль Токтомаматова</h5>
                <p class="card-subtitle text-muted mb-2">Отношения с клиентами</p>
                <p class="card-text">Дружелюбный характер и внимание к деталям Айгуль гарантируют, что потребности гостей всегда встречаются с улыбкой.</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center mb-4">
            <img src="https://randomuser.me/api/portraits/men/67.jpg" class="card-img-top" alt="Член команды">
            <div class="card-body">
                <h5 class="card-title">Бакыт Эсентаев</h5>
                <p class="card-subtitle text-muted mb-2">Шеф-повар</p>
                <p class="card-text">Кулинарное мастерство Бакыта привносит изысканные вкусы в наши блюда, восхищая гостей каждой трапезой.</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center mb-4">
            <img src="https://randomuser.me/api/portraits/women/28.jpg" class="card-img-top" alt="Член команды">
            <div class="card-body">
                <h5 class="card-title">Нургуль Жумабаева</h5>
                <p class="card-subtitle text-muted mb-2">Руководитель хозяйственной службы</p>
                <p class="card-text">Внимание к деталям Нургуль обеспечивает безупречное содержание наших номеров для комфорта наших гостей.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-center">
        <h2>Посетите нас сегодня</h2>
        <p class="lead mb-4">Испытайте роскошь и комфорт, которые предлагает наш отель</p>
        <a href="{{ route('rooms.index') }}" class="btn btn-primary btn-lg">Посмотреть наши номера</a>
    </div>
</div>
@endsection
