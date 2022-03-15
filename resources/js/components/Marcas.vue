<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- inicio card de busca -->
                <div class="card mb-3">
                    <div class="card-header">Busca de Marcas</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component titulo="id" id="inputId" id-help="idHelp" texto-ajuda="Digite o número de Referência da Marca">
                                    <input type="number" class="form-control" id="id" aria-describedby="idHelp">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="nome" id="inputNome" id-help="nomeHelp" texto-ajuda="Digite o nome da Marca">
                                    <input type="text" class="form-control" id="nome" aria-describedby="nomeHelp">
                                </input-container-component>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                    </div>
                </div>
                <!-- fim card de busca -->
                <!-- inicio card de listagem -->
                <div class="card">
                    <div class="card-header">Busca de Marcas</div>
                    <div class="card-body">
                        <div class="row">
                            <table-component></table-component>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm float-end">Adicionar</button>
                    </div>
                </div>

                <!-- inicio card de listagem -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
