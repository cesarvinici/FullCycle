export interface AddClientFacadeInputDTO {
    id?: string;
    name: string;
    email: string;
    document: string;
    street: string;
    number: string | number;
    complement: string;
    city: string;
    state: string;
    zipCode: string;
}

export interface FindClientFacadeInputDto {
    id: string;
}

export interface FindClientFacadeOutputDto {
    id: string;
    name: string;
    email: string;
    document: string;
    street: string;
    number: string;
    complement: string;
    city: string;
    state: string;
    zipCode: string;
    created_at: Date;
    updated_at: Date;
}

export default interface ClientAdmFacadeInterface {
    addClient(input: AddClientFacadeInputDTO): Promise<void>;
    findClient(input: FindClientFacadeInputDto): Promise<FindClientFacadeOutputDto>;
}