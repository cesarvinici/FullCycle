import Product from "../../../product-adm/domain/product-adm.entity";

export interface GenerateInvoiceInputDto {
    id?: string;
    name: string;
    document: string;
    street: string;
    number: number;
    complement: string;
    city: string;
    state: string;
    zipCode: string;
    items: Product[];
}

export interface GenerateInvoiceOutputDto {
    id: string;
    name: string;
    document: string;
    street: string;
    number: number;
    complement: string;
    city: string;
    state: string;
    zipCode: string;
    items: {
        id: string;
        name: string;
        price: number
    }[],
    total: number;
}
    