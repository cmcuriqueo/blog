@extends('layouts.app')

@section('title', 'Home' )

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <div class="text-center">
                                Todos los Articulos
                            </div>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">

                            @forelse ($articles as $article)
                            
                            <a href="/articles/{{ str_replace(' ', '-', $article->title) }}/" class="list-group-item">
                                <p class="list-group-item-text">{{ $article->title }}
                                    <div class="text-right">
                                        <span class="badge">{{ $article->category->name }}</span>
                                    </div>
                                </p>
                            </a>
                            @empty
                            <h3 class="text-center">No hay articulos!.</h3>
                            @endforelse

                        </div>
                    </div>
                    <div class="panel-footer">
                        {{ $articles->links() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            &nbsp;
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <div class="text-center">Ultimos</div>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                @forelse ($articlesUltimos as $article)
                                    <tr>
                                        <td>
                                            <a href="/articles/{{ str_replace(' ', '-', $article->title) }}/">{{ $article->title }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr> 
                                        <td><h3 class="text-center">No hay articulos!.</h3></td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>  
                    </div>
                </div>  
            </div>

            <div class="row">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <div class="text-center">Recomendados</div>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                @forelse ($articlesRecomendados as $article)
                                    <tr>
                                        <td>
                                            <a href="/articles/{{ str_replace(' ', '-', $article->title) }}/">{{ $article->title }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr> 
                                        <td><h3 class="text-center">No hay articulos!.</h3></td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @if(Session::has('success'))
                @include('layouts.push')
            @endif
        </div>
    </div>
</div>
@endsection
