<div class="col-md-5 mt-3">
    <div class="card">
        <div class="card-header">
            <h4>Avaliações ({{ $qtdAvaliacoes }})</h4>
        </div>
        <div class=" p-3">
            <div class="mt-2 row d-flex align-items-center">
                <div class="d-inline col-2 text-end">
                    <span class="text-nowrap ">{{$notas[10]['qtd']}}</span>
                </div>
                <div class="d-inline col-9">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                            role="progressbar" aria-label="Animated striped example"
                            aria-valuenow="{{$notas[10]['percent']}}" aria-valuemin="0" aria-valuemax="100"
                            style="width: {{$notas[10]['percent']}}%">
                            {{$notas[10]['percent']}}%
                        </div>
                    </div>
                </div>
                <div class="col-1 m-0 p-0">
                    <i class="fa-2x text-success fa-regular fa-face-laugh-beam"></i>
                </div>
            </div>

            <div class="mt-2 row d-flex align-items-center">
                <div class="d-inline col-2 text-end">
                    <span class="text-nowrap">{{$notas[8]['qtd']}}</span>
                </div>
                <div class="d-inline col-9">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                            role="progressbar" aria-label="Animated striped example"
                            aria-valuenow="{{$notas[8]['percent']}}" aria-valuemin="0" aria-valuemax="100"
                            style="width: {{$notas[8]['percent']}}%">
                            {{$notas[8]['percent']}}%
                        </div>
                    </div>
                </div>
                <div class="col-1 m-0 p-0">
                    <i class="fa-2x text-primary fa-regular fa-face-smile"></i>
                </div>
            </div>

            <div class="mt-2 row d-flex align-items-center">
                <div class="d-inline col-2 text-end">
                    <span class="text-nowrap">{{$notas[6]['qtd']}}</span>
                </div>
                <div class="d-inline col-9">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar"
                            aria-label="Animated striped example" aria-valuenow="{{$notas[6]['percent']}}"
                            aria-valuemin="0" aria-valuemax="100" style="width: {{$notas[6]['percent']}}%">
                            {{$notas[6]['percent']}}%
                        </div>
                    </div>
                </div>
                <div class="col-1 m-0 p-0">
                    <i class="fa-2x text-info fa-regular fa-face-meh"></i>
                </div>
            </div>

            <div class="mt-2 row d-flex align-items-center">
                <div class="d-inline col-2 text-end">
                    <span class="text-nowrap">{{$notas[4]['qtd']}}</span>
                </div>
                <div class="d-inline col-9">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                            role="progressbar" aria-label="Animated striped example"
                            aria-valuenow="{{$notas[4]['percent']}}" aria-valuemin="0" aria-valuemax="100"
                            style="width: {{$notas[4]['percent']}}%">
                            {{$notas[4]['percent']}}%
                        </div>
                    </div>
                </div>
                <div class="col-1 m-0 p-0">
                    <i class="fa-2x text-warning fa-regular fa-face-frown"></i>
                </div>
            </div>

            <div class="mt-2 row d-flex align-items-center">
                <div class="d-inline col-2 text-end">
                    <span class="text-nowrap">{{$notas[2]['qtd']}}</span>
                </div>
                <div class="d-inline col-9">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                            role="progressbar" aria-label="Animated striped example"
                            aria-valuenow="{{$notas[2]['percent']}}" aria-valuemin="0" aria-valuemax="100"
                            style="width: {{$notas[2]['percent']}}%">
                            {{$notas[2]['percent']}}%
                        </div>
                    </div>
                </div>
                <div class="col-1 m-0 p-0">
                    <i class="fa-2x text-danger fa-regular fa-face-angry d-inline"></i>
                </div>
            </div>
        </div>
    </div>
</div>