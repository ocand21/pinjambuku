<template>
    <div v-if="validationErrors">
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <ul>
                <li v-for="(value, key, index) in validationErrors">{{ value }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {}
</script>

<style lang="css" scoped>
</style>

<script>
export default {
    data() {
        return {

        }
    },
    props: ['errors'],
    computed: {
        validationErrors() {
            let errors = Object.values(this.errors);
            errors = errors.flat();
            return errors;
        }
    }
}
</script>
