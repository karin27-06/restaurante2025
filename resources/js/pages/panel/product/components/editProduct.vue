<template>
    <Dialog :open="modal" @update:open="clouseModal">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Editando el producto</DialogTitle>
                <DialogDescription>Los datos están validados, llenar con precaución.</DialogDescription>
            </DialogHeader>
            <form @submit="onSubmit" class="flex flex-col gap-4 py-4">
                <FormField v-slot="{ componentField }" name="name">
                    <FormItem>
                        <FormLabel>Nombre</FormLabel>
                        <FormControl>
                            <Input id="name" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="details">
                    <FormItem>
                        <FormLabel>Detalles</FormLabel>
                        <FormControl>
                            <Input id="details" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="idCategory">
                    <FormItem>
                        <FormLabel>Categoría</FormLabel>
                        <FormControl>
                            <Select v-bind="componentField" :disabled="loading">
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecciona una Categoría" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                                            {{ category.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="idAlmacen">
                    <FormItem>
                        <FormLabel>Almacén</FormLabel>
                        <FormControl>
                            <Select v-bind="componentField" :disabled="loading">
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecciona un almacén" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="almacen in almacenes" :key="almacen.id" :value="almacen.id">
                                            {{ almacen.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="state">
                    <FormItem>
                        <FormLabel>Estado</FormLabel>
                        <FormControl>
                            <Select v-bind="componentField">
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecciona el estado" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectLabel>Estado</SelectLabel>
                                        <SelectItem value="activo">Activo</SelectItem>
                                        <SelectItem value="inactivo">Inactivo</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <DialogFooter>
                    <Button type="submit">Guardar cambios</Button>
                    <Button type="button" variant="outline" @click="clouseModal">Cancelar</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>

<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { onMounted, ref, watch } from 'vue';
import * as z from 'zod';

import { ProductResource, ProductUpdateRequest } from '../interface/Product';

const props = defineProps<{ modal: boolean; productData: ProductResource }>();
const emit = defineEmits<{
    (e: 'emit-close', open: boolean): void;
    (e: 'update-product', product: ProductUpdateRequest, id_product: number): void;
}>();

const clouseModal = () => emit('emit-close', false);

// Schema de validación
const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2, 'El Nombre debe tener al menos 2 caracteres').max(100, 'Máximo 100 caracteres'),
        details: z.string().optional(), // Detalles puede ser vacío

        idCategory: z.number().min(1, 'Selecciona una categoría'),
        idAlmacen: z.number().min(1, 'Selecciona un almacén'), // Asegúrate de que sea un número
        state: z.enum(['activo', 'inactivo']),
    }),
);

// Inicialización del formulario
const { handleSubmit, setValues } = useForm({
    validationSchema: formSchema,
    initialValues: {
        name: props.productData.name,
        details: props.productData.details || '', // Agregado detalles
        idCategory: props.productData.category?.id || 0,
        idAlmacen: props.productData.almacen?.id || 0,
        state: props.productData.state ? 'activo' : 'inactivo',
    },
});

watch(
    () => props.productData,
    (newData) => {
        if (newData) {
            setValues({
                name: newData.name ?? '',
                details: newData.details ?? '',
                idCategory: newData.category?.id ?? 0,
                idAlmacen: newData.almacen?.id ?? 0,
                state: newData.state === true ? 'activo' : 'inactivo',
            });
        }
    },
    { deep: true, immediate: true },
);

const onSubmit = handleSubmit((values) => {
    const updatedProduct: ProductUpdateRequest = {
        name: values.name,
        details: values.details || '', // Si el campo detalles está vacío, lo dejamos vacío
        idCategory: values.idCategory, // Directamente el valor numérico
        idAlmacen: values.idAlmacen, // Lo mismo para id_almacen
        state: values.state === 'activo' ? 'activo' : 'inactivo', // Asegúrate de que el estado se envíe correctamente
    };

    // Emitimos la actualización del producto con su ID
    emit('update-product', updatedProduct, props.productData.id);
    clouseModal(); // Cerrar el modal después de la actualización
});

const almacenes = ref([]);
const categories = ref([]);
const loading = ref(false);

// Función para obtener los almacenes desde la API
const fetchAlmacenes = async () => {
    try {
        loading.value = true;
        const response = await fetch('/panel/almacens-option');
        const data = await response.json();
        almacenes.value = data.almacens;
    } catch (error) {
        console.error('Error al obtener los almacenes:', error);
    } finally {
        loading.value = false;
    }
};
// Función para obtener los almacenes desde la API
const fetchCategories = async () => {
    try {
        loading.value = true;
        const response = await fetch('/panel/categories-option');
        const data = await response.json();
        categories.value = data.categories;
    } catch (error) {
        console.error('Error al obtener las categorias:', error);
    } finally {
        loading.value = false;
    }
};

// Cargar almacenes cuando el componente se monta
onMounted(() => {
    fetchAlmacenes();
    fetchCategories();
});
</script>
