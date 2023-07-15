import Product from "../domain/Product.entity";

type GenericProducts = {
  id: string;
  name: string;
  price: number;
}

export interface GenerateInvoiceFacadeInputDTO {
    id?: string;
    name: string;
    document: string;
    street: string;
    number: number | string;
    complement: string;
    city: string;
    state: string;
    zipCode: string;
    items: Product[] | GenericProducts[];
}

export interface GenerateInvoiceFacadeOutputDTO {
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
    generateInvoice(input: GenerateInvoiceFacadeInputDTO): Promise<GenerateInvoiceFacadeOutputDTO>;
    findInvoice(input: FindInvoiceFacadeInputDTO): Promise<FindInvoiceFacadeOutputDTO>;
}