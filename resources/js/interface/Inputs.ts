export interface InputClientType {
    id: number;
    name: string;
}
export interface InputSupplier {
    id: number;
    name: string;
    ruc: string;
}
// export for autocomplete

export interface InputCustomer {
    id: number;
    name: string;
    ruc: string;
}

export interface InputCustomerResponse {
    data: InputCustomer[];
}

export interface InputSupplierResponse {
    data: InputSupplier[];
}

export interface InputClientTypeResponse {
    data: InputClientType[];
}
