<div class="col-md-7 mt-3">
    <div class="card">
        <div class="card-header">
            <h4>{{$title}}</h4>
        </div>
        <div class="">
            <div class=" p-3">
                <div class="d-flex justify-content-between align-items-end">
                    <div class="text-center text-danger">
                        <i
                            class="@if ($avaliacoesAverage < 1.5) fa-6x @else fa-4x @endif  fa-regular fa-face-angry"></i>
                        <br>
                        <span>Muito Ruim</span>
                    </div>
                    <div class="text-center text-warning">
                        <i
                            class="@if ($avaliacoesAverage >= 1.5 && $avaliacoesAverage < 2.5) fa-6x @else fa-4x @endif fa-regular fa-face-frown"></i>
                        <br>
                        <span>Ruim</span>
                    </div>
                    <div class="text-center text-info">
                        <i
                            class="@if ($avaliacoesAverage >= 2.5 && $avaliacoesAverage < 3.5) fa-6x @else fa-4x @endif  fa-regular fa-face-meh"></i>
                        <br>
                        <span>Neutro</span>
                    </div>

                    <div class="text-center text-primary">
                        <i
                            class="@if ($avaliacoesAverage >= 3.5 && $avaliacoesAverage < 4.5) fa-6x @else fa-4x @endif  fa-regular fa-face-smile"></i>
                        <br>
                        <span>Bom</span>
                    </div>
                    <div class="text-center text-success">
                        <i
                            class="@if ($avaliacoesAverage >= 4.5) fa-6x @else fa-4x @endif fa-regular fa-face-laugh-beam"></i>
                        <br>
                        <span>Muito Bom</span>
                    </div>
                </div>
                <div class="mt-2 progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated
                                    @if ($avaliacoesAverage < 1.5)
                                        bg-danger
                                    @else
                                        @if ($avaliacoesAverage >=  1.5 && $avaliacoesAverage < 2.5)
                                        bg-warning                            
                                        @else
                                        @if ($avaliacoesAverage >=  2.5 && $avaliacoesAverage < 3.5)
                                        bg-info                            
                                        @else
                                        @if ($avaliacoesAverage >=  3.5 && $avaliacoesAverage < 4.5)
                                        bg-primary                            
                                        @else
                                        bg-success                            
                                        @endif                
                                        @endif    
                                        @endif    
                                    @endif
                                    " role="progressbar" aria-label="Animated striped example"
                        aria-valuenow="{{$percentAverage}}" aria-valuemin="0" aria-valuemax="100"
                        style="width: {{$percentAverage}}%">
                        {{number_format($percentAverage, 2,',', '')}} %
                    </div>
                </div>
            </div>
            <div class="text-center">
                <span class="fs-3 fw-normal">Avaliação Geral:</span>
                <p class="fs-1 fw-bold 
                                    @if ($avaliacoesAverage < 1.5)
                                    text-danger
                                    @else
                                        @if ($avaliacoesAverage >=  1.5 && $avaliacoesAverage < 2.5)
                                        text-warning                            
                                        @else
                                            @if ($avaliacoesAverage >=  2.5 && $avaliacoesAverage < 3.5)
                                            text-info                            
                                            @else
                                                @if ($avaliacoesAverage >=  3.5 && $avaliacoesAverage < 4.5)
                                                text-primary                            
                                                @else
                                                text-success                            
                                                @endif              
                                            @endif    
                                        @endif    
                                    @endif">
                    {{ number_format($avaliacoesAverage, 2, ',', '') }}<span class="fs-3 fw-normal text-body">/5</span>
                </p>
            </div>
        </div>
    </div>
</div>