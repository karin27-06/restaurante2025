import {
    FloorDeleteResponse,
    FloorRequest,
    FloorResponse,
    FloorUpdateRequest,
    ShowFloorResponse,
} from '@/pages/panel/floor/interface/Floor';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

export const FloorServices = {
    // Listar pisos
    async index(page: number, name: string): Promise<FloorResponse> {
        const response = await axios.get(`/panel/listar-floors?page=${page}&name=${encodeURIComponent(name)}`);
        return response.data;
    },

    // Crear piso (Inertia)
    async store(data: FloorRequest) {
        router.post(route('panel.floors.store'), data);
    },

    // Mostrar piso por ID
    async show(id: number): Promise<ShowFloorResponse> {
        const response = await axios.get(`floors/${id}`);
        return response.data;
    },

    // Actualizar piso
    async update(id: number, data: FloorUpdateRequest): Promise<ShowFloorResponse> {
        const response = await axios.put(`floors/${id}`, data);
        return response.data;
    },

    // Eliminar piso
    async destroy(id: number): Promise<FloorDeleteResponse> {
        const response = await axios.delete(`floors/${id}`);
        return response.data;
    },
};
