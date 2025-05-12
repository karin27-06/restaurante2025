<template>
    <Head title="Nuevo Producto"></Head>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card class="mt-4 flex flex-col gap-4">
                <CardHeader>
                    <CardTitle>NUEVO PRODUCTO</CardTitle>
                    <CardDescription>Complete los campos para crear un nuevo producto</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit="onSubmit" class="flex flex-col gap-6">
                        <FormField v-slot="{ componentField }" name="name">
                            <FormItem>
                                <FormLabel>Nombre</FormLabel>
                                <FormControl>
                                    <Input type="text" placeholder="Nombre del producto" v-bind="componentField" />
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
            <SelectItem :value="'activo'">Activo</SelectItem>
            <SelectItem :value="'inactivo'">Inactivo</SelectItem>
          </SelectGroup>
        </SelectContent>
      </Select>
    </FormControl>
    <FormMessage />
  </FormItem>
</FormField>



                        <div class="container flex justify-end gap-4">
                            <Button type="submit" variant="default"> Enviar </Button>
                            <Button type="reset" variant="outline"> Borrar </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectGroup, SelectItem, SelectLabel } from '@/components/ui/select';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import * as z from 'zod';
import { onMounted, ref, watch } from 'vue';

//composable
import { useProduct } from '@/composables/useProduct';
const { createProduct } = useProduct();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Productos',
        href: '/panel/products',
    },
    {
        title: 'Crear Producto',
        href: '/panel/products/create',
    },
];

// Form validation
const formSchema = toTypedSchema(
    z.object({
        name: z
            .string({ message: 'Campo obligatorio' })
            .min(1, { message: 'Nombre mayor a 5 letras' })
            .max(100, { message: 'Nombre menor a 100 letras' }),
       details: z.string().optional(), // Detalles puede ser vacío

        idCategory: z.number().min(1, 'Selecciona una categoría'),
        idAlmacen: z.number().min(1, 'Selecciona un almacén'), // Asegúrate de que sea un número
        state: z.enum(['activo', 'inactivo']),
    }),
);

// Form submit
const { handleSubmit } = useForm({
    validationSchema: formSchema,
    initialValues: {         
        state: 'activo',     
    },
});

const onSubmit = handleSubmit((values) => {
    console.log('Valores enviados:', values);
    createProduct(values);
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

<style scoped></style>
