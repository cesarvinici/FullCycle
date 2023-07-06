import { Sequelize } from "sequelize-typescript";
import { ClientModel } from "../repository/client.model";
import ClientRepository from "../repository/client.repository";
import AddClientUsecase from "../usecase/add-client/add-client.usecase";
import ClientAdmFacade from "./client-adm.facade";
import FindClientUsecase from "../usecase/find-client/find-client.usecase";

describe("Client ADM facade test", () => {
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
        const repository = new ClientRepository();
        const addUseCase = new AddClientUsecase(repository);

        const facade = new ClientAdmFacade({ 
            addUseCase: addUseCase,
            findUseCase: undefined
         });

        
        const input = {
            id: "1",
            name: "John Doe",
            email: "john@doe.com",
            address: "John Doe Street"
        }

        await facade.addClient(input);

        const client = await ClientModel.findOne({ where: { id: "1" } });

        expect(client).not.toBeNull();
        expect(client.id).toBe("1");
        expect(client.name).toBe("John Doe");
        expect(client.email).toBe("john@doe.com");
        expect(client.address).toBe("John Doe Street");
    });

    it("should find a client", async () => {
        const repository = new ClientRepository();

        const facade = new ClientAdmFacade({
            addUseCase: undefined,
            findUseCase: new FindClientUsecase(repository)
        });


        ClientModel.create({ 
                id: "1",
                name: "John Doe",
                email: "john@doe.com",
                address: "John Doe Street",
                created_at: new Date(),
                updated_at: new Date()
        });

        await facade.findClient({ id: "1" }).then((client) => {
            expect(client).not.toBeNull();
            expect(client.id).toBe("1");
            expect(client.name).toBe("John Doe");
            expect(client.email).toBe("john@doe.com");
            expect(client.address).toBe("John Doe Street");
        });

    });
});