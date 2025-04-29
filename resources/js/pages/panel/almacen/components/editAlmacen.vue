<template>
    <Dialog :open="modal" @update:open="clouseModal">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Editando el Almacen</DialogTitle>
                <DialogDescription>Los datos están validados, llenar con precaución.</DialogDescription>
            </DialogHeader>
            <form @submit="onSubmit" class="flex flex-col gap-4 py-4">
                <!-- Campo para editar el nombre del Almacen -->
                <FormField v-slot="{ componentField }" name="name">
                    <FormItem>
                        <FormLabel>Almacen</FormLabel>
                        <FormControl>
                            <Input id="name" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <!-- Campo para elegir el estado del tipo de Almacen -->
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
import { watch } from 'vue';
import * as z from 'zod';

import { AlmacenResource, AlmacenUpdateRequest } from '../interface/Almacen';

const props = defineProps<{ modal: boolean; almacenData: AlmacenResource }>();
const emit = defineEmits<{
    (e: 'emit-close', open: boolean): void;
    (e: 'update-almacen', almacen: AlmacenUpdateRequest, id_Almacen: number): void;
}>();

const clouseModal = () => emit('emit-close', false);

//Schema de validación
const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(3, 'El nombre es requerido').max(50, 'El nombre no puede tener más de 50 caracteres'),
        state: z.enum(['activo', 'inactivo']),
    }),
);

//Inicialización del formulario
const { handleSubmit, setValues } = useForm({
    validationSchema: formSchema,
    initialValues: {
        name: props.almacenData.name,
        state: props.almacenData.state ? 'activo' : 'inactivo',
    },
});

watch(
    () => props.almacenData,
    (newData) => {
        if (newData) {
            setValues({
                name: newData.name,
                state: newData.state ? 'activo' : 'inactivo',
            });
        }
    },
    { deep: true, immediate: true },
);

const onSubmit = handleSubmit((values) => {
    console.log('Formulario enviado con: ', values);
    emit('update-almacen', values, props.almacenData.id);
    clouseModal();
});
</script>
<style scoped lang="css"></style>
