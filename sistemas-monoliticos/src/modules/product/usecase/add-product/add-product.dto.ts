export interface AddProductInputDto {
    id?: string;
    name: string;
    price: number;
}

export interface AddProductOutputDto {
    id: string;
    name: string;
    price: number;
    createdAt: Date;
    updatedAt: Date;
}