export interface FindClientInputDto {
    id: string;
}

export interface FindClientOutputDto {
    id: string;
    name: string;
    email: string;
    address: string;
    created_at: Date;
    updated_at: Date;
}