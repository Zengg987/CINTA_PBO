<template>
    <q-page class="q-pa-md">
        <q-btn @click="dialogBerita = true" label="input berita" icon="add" class="q-mb-sm" color="primary" />
        <q-markup-table bordered separator="cell" wrap-cells>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Isi</th>
                    <th>Gambar</th>
                    <th>Penulis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, index) in berita" :key="index++">
                    <td>{{ index }}</td>
                    <td>{{ row.judul }}</td>
                    <td>
                        <p v-html="row.isi" />
                    </td>
                    <td>
                        <q-img v-if="row.gambar" :src="/storage/ + row.gambar" />
                    </td>
                    <td>
                        {{ row.penulis }}
                    </td>
                    <td>
                        <q-btn>
                            <q-icon round name="expand_more" />
                            <q-menu>
                                <q-list dense>
                                    <q-item clickable v-close-popup @click="inUpload(row.id)">
                                        <q-item-section side>
                                            <q-icon name="upload" />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label>
                                                upload
                                            </q-item-label>
                                        </q-item-section>
                                    </q-item>

                                    <q-item clickable v-close-popup>
                                        <q-item-section side>
                                            <q-icon name="edit" />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label>
                                                edit
                                            </q-item-label>
                                        </q-item-section>
                                    </q-item>

                                    <q-item clickable v-close-popup>
                                        <q-item-section side>
                                            <q-icon name="delete" />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label>
                                                delete
                                            </q-item-label>
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-menu>
                        </q-btn>
                    </td>
                </tr>
            </tbody>
        </q-markup-table>
        <q-dialog v-model="dialogBerita" transition-show="jump-right" transition-hide="jump-left">
            <q-card style="width: 700px;">
                <q-toolbar>
                    <q-toolbar-title>Upload Gambar</q-toolbar-title>
                    <q-space />
                    <q-btn @click="batal" round flat icon="cancel" />
                </q-toolbar>
                <q-card-section>
                    <q-form class="q-gutter-sm">
                        <q-input outlined label="judul berita" v-model="form.judul" />
                        <q-editor v-model="form.isi" min-height="10rem" />
                        <div class="text-right">
                            <q-btn  @click="simpan" label="simpan" />
                            <q-btn @click="batal" label="batal" color="teal" class="q-mx-sm" />
                        </div>
                    </q-form>
                    <q-form></q-form>
                </q-card-section>
            </q-card>
        </q-dialog>
        <q-dialog v-model="dialogGambar">
            <q-card style="width: 700px;">
                <q-card-section>
                    <q-form class="q-gutter-sm">
                        <q-file class="q-mb-sm" outlined label="Upload img" v-model="filex.gambar"
                            @input="filex.gambar = $event.target.files[0]" />
                        <div>
                            <q-btn label="upload" color="primary" class="q-mx-xs" @click="upload" />
                            <q-btn label="batal" color="grey" @click="batal" />
                        </div>
                    </q-form>
                </q-card-section>
            </q-card>
        </q-dialog>
    </q-page>
</template>

<script>
import layout from "../layout/layout.vue";
import { ref, reactive } from 'vue';
import { router, useForm } from "@inertiajs/vue3";
export default {
    layout: layout,
    props: {
        berita: Array
    },
    setup() {
        const dialogGambar = ref(false)
        const dialogBerita = ref(false)
        const filex = useForm({
            gambar: null
        })
        const form = reactive({
            id: "",
            judul: "",
            isi: "",

        })
        function batal() {
            form.id = "",
            form.judul = "",
            form.isi = "",
            dialogBerita.value = false
            dialogGambar.value = false
        }
        function simpan() {
            router.visit('/berita/simpan', {
                method: 'post',
                data: {
                    id: form.id,
                    judul: form.judul,
                    isi: form.isi,
                },
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    batal()
                }
            }
            )
        }
        function inUpload(id) {
            form.id=id
            dialogGambar.value = true
        }
        function upload() {
            console.log(filex.gambar)
            router.visit('/berita/simpan', {
                method: 'post',
                data: {
                    id: form.id,
                    gambar: filex.gambar
                },
                forceFormData: true,
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    batal()
                }
            }
            )
        }

        return {

            dialogBerita,
            dialogGambar,
            form,
            filex,
            inUpload,
            batal,
            upload,
            simpan,
            form
        }
    }

}
</script>

<style></style>