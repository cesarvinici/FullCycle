export interface ListProductInputDto {}


export type Product = {
    id: string;
    name: string;
    price: number;
}

export interface ListProductOutputDto {
    products: Product[];
}