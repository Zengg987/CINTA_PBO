<template>
    <q-page class="q-pa-md">
        <div class="q-mx-xl" style="max-width: 1300px; margin: 0 auto;">
            <div class="row q-col-gutter-md">
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <q-card flat v-for="b1 in berita1" :key="b1" >
                        <img  :src="'/storage/' + b1.gambar" alt="tau">
                        <q-card-section>
                            <div class="text-h6">{{ b1.judul }}</div>
                            <div>
                                <p v-html="b1.isi"></p>
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <q-card flat>
                        <q-list class="q-mx-sm">
                            <q-item v-for="b2 in berita2" :key="b2" class="q-mb-md">
                                <q-item-section thumbnail top>
                                        <img :src="'/storage/' + b2.gambar" alt="tau">
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label>
                                        {{ b2.judul }}
                                    </q-item-label>
                                    <q-item-label section>
                                        <p v-html="b2.isi.substring(0,100)+'...'"></p>
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-card>
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <q-list>
                        <q-item>
                            <span class="text-h6">Trending</span>
                        </q-item>
                        <q-separator class="q-mb-md"/>
                        <q-item v-for="tr in trending" :key="tr" class="q-mb-md">
                            <q-item-section>
                                <q-itemlabel class="text-h5">{{ tr.judul }}</q-itemlabel>
                                <q-itemlabel><p v-html="tr.isi.substring(0,100)+'...'"></p></q-itemlabel>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </div>

            </div>
        </div>
    </q-page>
</template>

<script>
import axios from 'axios';
import layout from "../layout/layout.vue";
import { onMounted, ref } from 'vue';

export default {
    layout: layout,

    setup() {
        const berita1 = ref([])
        const berita2 = ref([])
        const trending = ref([])

        function getberita() {
            axios.get('/getberita').then((res) => {
                berita1.value = res.data.berita1
                berita2.value = res.data.berita2
                trending.value = res.data.trending
            })
        }

        onMounted(() => {
            getberita()
        })
        return {
            berita1,
            berita2,
            trending
        }
    }

};


</script>

