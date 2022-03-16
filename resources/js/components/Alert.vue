<template>
    
    <div :class="style" role="alert">
        {{ titulo }} - {{ detalhes.text }}

        <ul v-if="detalhes.error">
            <li v-for="e,key in detalhes.error" :key="key">{{ e[0] }}</li>
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
