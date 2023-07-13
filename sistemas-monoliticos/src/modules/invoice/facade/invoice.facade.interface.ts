import Product from "../../product/domain/product.entity";

export interface GenerateInvoiceFacadeInputDTO {
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

export interface FindInvoiceFacadeInputDTO {
    id: string;
}

export interface FindInvoiceFacadeOutputDTO {
    id: string;
    name: string;
    document: string;
    address: {
      street: string;
      number: string;
      complement: string;
      city: string;
      state: string;
      zipCode: string;
    };
    items: {
      id: string;
      name: string;
      price: number;
    }[];
    total: number;
    createdAt: Date;
}

export default interface InvoiceFacadeInterface {
    generateInvoice(input: GenerateInvoiceFacadeInputDTO): Promise<void>;
    findInvoice(input: FindInvoiceFacadeInputDTO): Promise<FindInvoiceFacadeOutputDTO>;
}