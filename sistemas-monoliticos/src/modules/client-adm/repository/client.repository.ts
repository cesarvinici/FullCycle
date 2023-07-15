import Id from "../../@shared/domain/value-object/id.value-object";
import Client from "../domain/client.entity";
import ClientAdmGateway from "../gateway/client-adm.gateway";
import { ClientModel } from "./client.model";

export default class ClientRepository implements ClientAdmGateway {

    async add(client: Client): Promise<void> {
        await ClientModel.create({
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
        });
    }

    async find(id: string): Promise<Client> {
        
        const client = await ClientModel.findOne({
            where: {
                id: id
            }
        })

        if( !client ) {
            throw new Error("Client not found");
        }

        return new Client({
            id: new Id(client.id),
            name: client.name,
            email: client.email,
            document: client.document,
            street: client.street,
            number: client.number,
            complement: client.complement,
            city: client.city,
            state: client.state,
            zipCode: client.zipCode,
            created_at: client.created_at,
            updated_at: client.updated_at
        });


    }
}