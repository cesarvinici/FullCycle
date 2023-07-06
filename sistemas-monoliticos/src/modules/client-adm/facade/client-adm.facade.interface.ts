export interface AddClientFacadeInputDTO {
    id?: string;
    name: string;
    email: string;
    address: string;
}

export interface FindClientFacadeInputDto {
    id: string;
}

export interface FindClientFacadeOutputDto {
    id: string;
    name: string;
    email: string;
    address: string;
    created_at: Date;
    updated_at: Date;
}

export default interface ClientAdmFacadeInterface {
    addClient(input: AddClientFacadeInputDTO): Promise<void>;
    findClient(input: FindClientFacadeInputDto): Promise<FindClientFacadeOutputDto>;
}