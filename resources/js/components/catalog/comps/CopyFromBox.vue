<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Чью начинку взять?</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <Loader v-if="views.loading"></Loader>

            <ul v-if="!views.loading" class="list-group">
                <li v-for="box in boxes" @click="save(box.id)" class="list-group-item list-group-item-action cursor-pointer">
                    {{ box.name }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import Loader from '../../Loader.vue'

export default {
    data() {
        return {
            boxes: [],

            selected: {
                box: '',
            },

            views: {
                loading: true,
            }
        }
    },
    created() {
        this.loadBoxes()
    },
    methods: {
        loadBoxes() {
            axios
            .get(`/api/catalog/boxes`)
            .then(response => {
                this.boxes = response.data

                this.views.loading = false
            })
        },
        save(boxID) {
            axios.get(`/api/catalog/box/${boxID}`)
            .then((response => {
                this.$parent.copyStockItemsFromAnotherBox(response.data)
                this.$parent.closeOffcanvas()
            }))
        },
    },
    components: {
        Loader,
    },
}
</script>
