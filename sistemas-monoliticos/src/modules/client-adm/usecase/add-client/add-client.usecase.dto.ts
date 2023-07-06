export interface AddClientInputDTO {
    id?: string;
    name: string;
    email: string;
    address: string;
}

export interface AddClientOutputDTO {
    id: string;
    name: string;
    email: string;
    address: string;
    created_at: Date;
    updated_at: Date;
}