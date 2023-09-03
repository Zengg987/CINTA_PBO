<template>
  <q-page class="q-pa-md">
    <q-btn @click="dialogUser =true"  label="tambah user" class="q-mb-md" icon="add" color="primary"/>
    <q-markup-table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(row, index) in data" :key="index++">
                <td>{{ index }}</td>
                <td>{{ row.name }}</td>
                <td>{{ row.username }}</td>
                <td>
                    <q-btn class="q-mx-xs" @click="edit(row.id)" unelevated icon="edit" color="teal"/>
                    <q-btn class="q-mx-xs" unelevated @click="konfirmasi(row.id)"  icon="delete" color="red"/>
                </td> 
                <td></td>
            </tr>
        </tbody>
    </q-markup-table>
  </q-page>
  <q-dialog v-model="dialogUser">
    <q-card flat style="width: 700px;">
        <q-toolbar>
            <q-toolbar-title>user</q-toolbar-title>
            <q-space/>
            <q-btn @click="batal" round flat icon="cancel"/>
        </q-toolbar>
        <q-card-section>
            <q-form class="q-gutter-sm">
                <q-input label="nama" v-model="form.name"/>
                <q-input label="username" v-model="form.username"/>
                <q-input label="password" v-model="form.password"/>
                <q-input label="email" v-model="form.email"/>

                <div>
                    <q-btn @click="simpan" class="q-ml-sm" label="simpan" icon="save" color="primary"/>
                    <q-btn @click="batal" class="q-ml-sm"  label="batal"  icon="cancel" color="red"/>
                </div>
            </q-form>
        </q-card-section>
    </q-card>
 </q-dialog>
    <q-dialog v-model="dialogKonfirm">
    <q-card style="width: 500px;">
    <q-card-section>
        <p>Apakah anda yakin ingin menghapus data ini</p>
        <div>
            <q-btn label="ya,hapus" @click="hapus" color="red"/>
            <q-btn label="tidak" @click="dialogKonfirm=false" class="q-mx-xs"/>
        </div>
    </q-card-section>
</q-card>
    </q-dialog>
</template>

<script>

import { ref, reactive } from 'vue';
import layout from '../layout/layout.vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

export default {
    layout:layout,
    props:{
        data: Array
    },
    setup(){
        const dialogUser=ref(false)
        const dialogKonfirm=ref(false)
        const form = reactive({
            id:"",
            name:"",
            username:"",
            password:"",
            email:"",
        })

        function batal(){
            form.id=""
            form.name=""
            form.username=""
            form.password=""
            form.email=""
            dialogUser.value=false

        }

        function simpan(){
           router.visit('/user/simpan',{
                method:'post',
                data:{
                    id:form.id,
                    name:form.name,
                    username:form.username,
                    password:form.password,
                    email:form.email,
                },
                preserveScroll:true,
                preserveState:true,
                onSuccess:()=>{
                    dialogUser.value=false
                }
           })
        }

        function edit(id){
            axios.get('/user/edit/'+id).then((res)=>{
                form.id=res.data.id
                form.name=res.data.name
                form.username=res.data.username
                form.email=res.data.email
                dialogUser.value=true
            })
        }

        function konfirmasi(id){
            form.id=id
            dialogKonfirm.value=true
        }

        function hapus(){
            router.visit("/user/hapus/"+form.id,({
                method:'delete',
                preserveScroll:true,
                preserveState:true,
                onSuccess:()=>(
                    dialogKonfirm.value=false
                )
            }))
        }

        return{
            hapus,
            konfirmasi,
            form,
            dialogUser,
            dialogKonfirm,
            batal,
            simpan,
            edit,
        }
    }
}
</script>

<style>

</style>