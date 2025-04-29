import { AlmacenDeleteResponse, AlmacenRequest, AlmacenResponse, showAlmacenResponse } from '@/pages/panel/almacen/interface/Almacen';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

export const AlmacenServices = {
    //List almacens
    async index(page: number, name: string): Promise<AlmacenResponse> {
        const response = await axios.get(`/panel/listar-almacens?page=${page}&name=${encodeURIComponent(name)}`);
        return response.data;
    },

    //Inertia
    async store(data: AlmacenRequest) {
        router.post(route('panel.almacens.store'), data);
    },

    //Show almacen
    async show(id: number): Promise<showAlmacenResponse> {
        const response = await axios.get(`/panel/almacens/${id}`);
        return response.data;
    },

    //Update almacen
    async update(id: number, data: AlmacenRequest): Promise<showAlmacenResponse> {
        const response = await axios.put(`/panel/almacens/${id}`, data);
        return response.data;
    },

    //Delete almacen
    async destroy(id: number): Promise<AlmacenDeleteResponse> {
        const response = await axios.delete(`/panel/almacens/${id}`);
        return response.data;
    },
};
