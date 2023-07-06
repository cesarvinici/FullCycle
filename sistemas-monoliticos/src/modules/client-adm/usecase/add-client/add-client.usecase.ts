import Id from "../../../@shared/domain/value-object/id.value-object";
import Client from "../../domain/client.entity";
import ClientAdmGateway from "../../gateway/client-adm.gateway";
import { AddClientInputDTO, AddClientOutputDTO } from "./add-client.usecase.dto";

export default class AddClientUsecase {

    private _clientRepository: ClientAdmGateway

    constructor(clientRepository: ClientAdmGateway) {
        this._clientRepository = clientRepository;
    }

    async execute(input: AddClientInputDTO): Promise<AddClientOutputDTO> {

        const props = {
            id: new Id(input.id),
            name: input.name,
            email: input.email,
            address: input.address
        }

        const client = new Client(props);

        await this._clientRepository.add(client);
        
        return {
            id: client.id.id,
            name: client.name,
            email: client.email,
            address: client.address,
            created_at: client.createdAt,
            updated_at: client.updatedAt
        }
    }
}