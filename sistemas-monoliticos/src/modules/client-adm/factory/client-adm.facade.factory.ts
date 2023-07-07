import ClientAdmFacade from "../facade/client-adm.facade";
import ClientRepository from "../repository/client.repository";
import AddClientUsecase from "../usecase/add-client/add-client.usecase";
import FindClientUsecase from "../usecase/find-client/find-client.usecase";

export default class ClientAdmFacadeFactory {

    static create() {

        const repository = new ClientRepository();
        const addUseCase = new AddClientUsecase(repository);
        const findUseCase = new FindClientUsecase(repository);

        return new ClientAdmFacade({
            addUseCase: addUseCase,
            findUseCase: findUseCase
        });
    }
}