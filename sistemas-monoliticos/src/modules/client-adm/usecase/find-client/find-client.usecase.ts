import ClientAdmGateway from "../../gateway/client-adm.gateway";
import { FindClientInputDto, FindClientOutputDto } from "./find-client.usecase.dto";

export default class FindClientUsecase {
    private _clientRepository: ClientAdmGateway
    
    constructor(clientRepository: ClientAdmGateway) {
        this._clientRepository = clientRepository;
    }

    async execute(input: FindClientInputDto): Promise<FindClientOutputDto> {
        
        const client = await this._clientRepository.find(input.id);

        return {
            id: client.id.id,
            name: client.name,
            email: client.email,
            document: client.document,
            street: client.street,
            number: client.number,
            complement: client.complement,
            city: client.city,
            state: client.state,
            zipCode: client.zipCode,
            created_at: client.createdAt,
            updated_at: client.updatedAt
        }
    }


}