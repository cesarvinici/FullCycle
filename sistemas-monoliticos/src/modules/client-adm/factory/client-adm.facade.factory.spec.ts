import { Sequelize } from "sequelize-typescript";
import { ClientModel } from "../repository/client.model";
import ClientAdmFacadeFactory from "./client-adm.facade.factory";

describe("Client ADM facade factory test", () => {
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

    it("should create a client", async () => {


        const facede = ClientAdmFacadeFactory.create();

        const input = {
            id: "1",
            name: "John Doe",
            email: "example@email.com",
            document: "123456789",
            street: "John Doe Street",
            number: "123",
            complement: "ap 123",
            city: "John Doe City",
            state: "John Doe State",
            zipCode: "12345678",
        }

        await facede.addClient(input);

        const client = await ClientModel.findOne({ where: { id: "1" } });

        expect(client).not.toBeNull();
        expect(client.id).toBe("1");
        expect(client.name).toBe("John Doe");
        expect(client.email).toBe(input.email);
        expect(client.street).toBe(input.street);

    });

    it("should find a client", async () => {
            
        const facede = ClientAdmFacadeFactory.create();

        const input = {
            id: "1",
            name: "John Doe",
            email: "example@email.com",
            document: "123456789",
            street: "John Doe Street",
            number: "123",
            complement: "ap 123",
            city: "John Doe City",
            state: "John Doe State",
            zipCode: "12345678",
            created_at: new Date(),
            updated_at: new Date()
        }

        ClientModel.create(input);

        const client = await facede.findClient({ id: "1" });

        expect(client).not.toBeNull();
        expect(client.id).toBe("1");
        expect(client.name).toBe("John Doe");
        expect(client.email).toBe(input.email);
        expect(client.street).toBe(input.street);
    });
});