import Client from "../domain/client.entity"

export default interface ClientAdmGateway {
    add(client: any): Promise<void>
    find(id: string): Promise<Client>
}