<table class="table">
  <thead>
    <tr>
      <th> </th>
      <th>Titular</th>
      <th>Autor</th>
      <th>Temas</th>
      <th>Publicación</th>
      <th>Status</th>
      <th> </th>
    </tr>
  </thead>
  <tbody>
    @foreach($articles as $article)
    <tr>
      <td><span class="badge rounded-pill badge-light-secondary me-1"><strong>{{ $article->id }}</strong></span></td>
      <td>{{ $article->title }}</td>
      <td>{{ $article->author->name }}</td>

      <td>
        <ul>
        @foreach($article->articletag as $tag)
        <li type="circle">{{ $tag['tag']->name }}</li>
        @endforeach
        </ul>
      </td>

      <td>{{ date("d/m/Y", strtotime($article->created_at)) }}</td>

      @if($article->status->code == 'ARC')
      <td><span class="badge rounded-pill badge-light-danger me-1">{{ $article->status->name }}</span></td>
      @endif

      @if($article->status->code == 'BOR')
      <td><span class="badge rounded-pill badge-light-warning me-1">{{ $article->status->name }}</span></td>
      @endif

      @if($article->status->code == 'PUB')
      <td><span class="badge rounded-pill badge-light-success me-1">{{ $article->status->name }}</span></td>
      @endif

      <td>
        <div class="dropdown">
          <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
          </button>
          <div class="dropdown-menu dropdown-menu-end">
            <a value="{{ $article->id }}" id="editArticles" class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editArticleModal">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
              <span>Editar</span>
            </a>
            <a value="{{ $article->id }}" id="deteleArticles" class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deteleArticleModal">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
              <span>Eliminar</span>
            </a>
          </div>
        </div>
      </td>
    </tr>


    @endforeach
  </tbody>
</table>

<hr class="my-0" />

<div id="paginatorTableArticles" class="d-flex justify-content-between p-2">

Mostrando {{ $articles->firstItem() }} - {{ $articles->lastItem() }} de un total de {{ $articles->total() }} registros

{{ $articles->links() }}

</div>             
