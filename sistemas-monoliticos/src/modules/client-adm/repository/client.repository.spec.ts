import { Sequelize } from "sequelize-typescript";
import { ClientModel } from "./client.model";
import ClientRepository from "./client.repository";
import Client from "../domain/client.entity";

describe("Client repository tests", () => {
    let sequelize: Sequelize;

    beforeEach(async () => {
        sequelize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true },
        });
        sequelize.addModels([ClientModel])
        await sequelize.sync();
    });

    afterEach(async () => {
        await sequelize.close();
    });


    it("Should find a client", async () => {
        await ClientModel.create({
            id: "1",
            name: "John Doe",
            email: "john@email.com",
            document: "123456789",
            street: "John Doe Street",
            number: "123",
            complement: "ap 123",
            city: "John Doe City",
            state: "John Doe State",
            zipCode: "12345678",
            created_at: new Date(),
            updated_at: new Date()
        });


        const clientRepository = new ClientRepository();

        const client = await clientRepository.find("1");

        expect(client.id.id).toBe("1");
        expect(client.name).toBe("John Doe");
        expect(client.email).toBe("john@email.com");
        expect(client.document).toBe("123456789");
        expect(client.street).toBe("John Doe Street");
        expect(client.createdAt).toBeInstanceOf(Date);
        expect(client.updatedAt).toBeInstanceOf(Date);
        
    });
    
    it("Should add a client", async () => {


        const clientRepository = new ClientRepository();

        const client = new Client({
            name: "John Doe",
            email: "john@email.com",
            document: "123456789",
            street: "John Doe Street",
            number: "123",
            complement: "ap 123",
            city: "John Doe City",
            state: "John Doe State",
            zipCode: "12345678",
        });


        await clientRepository.add(client);
        
        const clientModel = await ClientModel.findOne({
            where: {
                id: client.id.id
            }
        });

        expect(clientModel.id).toBe(client.id.id);
        expect(clientModel.name).toBe(client.name);
        expect(clientModel.email).toBe(client.email);
        expect(clientModel.street).toBe(client.street);
        expect(clientModel.created_at).toStrictEqual(client.createdAt);
        expect(clientModel.updated_at).toStrictEqual(client.updatedAt);
    });
});