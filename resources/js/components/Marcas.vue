<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- inicio card de busca -->
                <card-component titulo="Busca de Marcas">
                    <template v-slot:body>
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
                    </template>
                    <template v-slot:footer>
                        <button type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                    </template>
                </card-component>

                <!-- fim card de busca -->

                <!-- inicio card de listagem -->
                <card-component titulo="Lista de Marcas">
                    <template v-slot:body>
                        <table-component></table-component>
                    </template>
                    <template v-slot:footer>
                        <button type="submit" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
                <!-- fim card de listagem -->
            </div>
        </div>
        <modal-component id="modalMarca" titulo="Cadastrar Marca">
            <template v-slot:alert>
                <alert-component tipo="success" :detalhes="message" titulo="Seu registro foi cadastrado com sucesso!" v-if="status == 'ok'"></alert-component>
                <alert-component tipo="danger" :detalhes="message" titulo="Erro ao cadastrar a Marca" v-if="status == 'fail'"></alert-component>
            </template>
            <template v-slot:body>
                <div class="form-group"> 
                    <input-container-component titulo="Nome da Marca" id="cNome" id-help="cNomeHelp" texto-ajuda="Digite o nome da Marca para Cadastro">
                        <input type="text" class="form-control" id="cNome" aria-describedby="cNomeHelp" v-model="nomeMarca">
                    </input-container-component>

                </div>

                <div class="form-group"> 
                    <input-container-component titulo="Selecione uma Imagem" id="imagem" id-help="imagemHelp" texto-ajuda="">
                        <input type="file" class="form-control-file" id="imagem" aria-describedby="imagemHelp" @change="carregarImagem($event)">
                    </input-container-component>


                </div>

            </template>

            <template v-slot:footer>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>

            </template>

        </modal-component>
    </div>
</template>

<script>
    export default {
        computed:{
                token(){
                    let token = document.cookie.split(';').find(indice=>{
                        return indice.includes('token=')
                    })
                    token = 'Bearer ' + token.split('=')[1]
                    return token;
                }
            },
        data(){
            return{
                urlBase: 'http://localhost:8000/api/v1/marca',
                nomeMarca:'',
                arquivoImagem:[],
                status: '',
                message:'',
            }
        },
        methods:{
            
            carregarImagem(e){
                this.arquivoImagem = e.target.files
            },
            salvar(){
                let formData = new FormData();
                formData.append('nome',this.nomeMarca)
                formData.append('imagem',this.arquivoImagem[0])

                let config = {
                    headers:{
                        'Content-Type' : 'multipart/form-data',
                        'Accept' : 'application/json',
                        'Authorization' : this.token
                    }
                }
                console.log(this.nomeMarca,this.arquivoImagem)
                axios.post(this.urlBase,formData,config)
                    .then(response => {
                        this.status = 'ok'
                        this.message = {text :'Id do registro : ' + response.data.id }
                        
                    })
                    .catch(errors => {
                        this.status = 'fail'
                        this.message = {
                            text: errors.response.data.message,
                            error: errors.response.data.errors
                            
                            }
                    })
            }
        }
    }
</script>
