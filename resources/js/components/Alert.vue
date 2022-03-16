<template>
    
    <div :class="style" role="alert">
        {{ titulo }} - 
        <span v-if="detalhes.data.message">{{ detalhes.data.message  }}</span>
        <span v-if="detalhes.data.id">Id do registro : {{ detalhes.data.id  }}</span> 
        <ul v-if="detalhes.data.errors">
            <li v-for="e,key in detalhes.data.errors" :key="key">{{ e[0] }}</li>
        </ul>
    </div>

</template>

<script>
    export default {
        props:['tipo','titulo','detalhes'],
        computed: {
            style(){
                return 'alert alert-'+this.tipo
            }
        }
    }
</script>
