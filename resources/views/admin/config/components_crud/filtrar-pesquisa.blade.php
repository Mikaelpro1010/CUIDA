<form class="" action="{{ route($route) }}" method="GET">
    <div class="m-0 p-0 row">
        <div class="col-md-5">
            <label for="pesquisa">Nome:</label>
            <input id="pesquisa" class="form-control" type="text" name="pesquisa" placeholder="Pesquisar"
                value="{{ request()->pesquisa }}">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary form-control mt-3" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
                Buscar
            </button>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a id="btnLimpaForm" class="btn btn-warning form-control mt-3">
                Limpar
                <i class="fa-solid fa-eraser"></i>
            </a>
        </div>
    </div>

</form>