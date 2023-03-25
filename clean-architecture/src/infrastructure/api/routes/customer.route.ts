import express, {Request, Response } from 'express';
import CreateCustomerUseCase from '../../../usecase/customer/create/create.customer.usercase';
import FindCustomerUseCase from '../../../usecase/customer/find/find.customer.usercase';
import ListCustomerUseCase from '../../../usecase/customer/list/list.customer.usercase';
import UpdateCustomerUseCase from '../../../usecase/customer/update/update.customer.usercase';
import CustomerRepository from '../../customer/repository/sequilize/customer.repository';

export const customerRoute = express.Router();

customerRoute.post('/', async (req: Request, res: Response) => {
    const useCase = new CreateCustomerUseCase(new CustomerRepository());

    try {
        const customerDto = {
            name: req.body.name,
            address: {
                street: req.body.address.street,
                number: req.body.address.number,
                city: req.body.address.city,
                state: req.body.address.state,
                zip: req.body.address.zip,
            }
        }
        const customer = await  useCase.execute(customerDto);
        res.status(200).json(customer);
    } catch (error: any) {
        res.status(500).json({ error: error.message });
    }
});

customerRoute.get('/', async (req: Request, res: Response) => {
    const useCase = new ListCustomerUseCase(new CustomerRepository());

    try {
        const customers = await useCase.execute({});
        res.status(200).json(customers);
    } catch (error: any) {
        res.status(500).json({ error: error.message });
    }
});

customerRoute.get('/:id', async (req: Request, res: Response) => {
    const useCase = new FindCustomerUseCase(new CustomerRepository());

    try {
        const customers = await useCase.execute({ id: req.params.id });
        res.status(200).json(customers);
    } catch (error: any) {
        res.status(500).json({ error: error.message });
    }
});

customerRoute.post("/:id", async (req: Request, res: Response) => {
    const useCase = new UpdateCustomerUseCase(new CustomerRepository());

    try {
        const customerDto = {
            id: req.params.id,
            name: req.body.name,
            address: {
                street: req.body.address.street,
                number: req.body.address.number,
                city: req.body.address.city,
                state: req.body.address.state,
                zip: req.body.address.zip,
            }
        }
        const customer = await  useCase.execute(customerDto);
        res.status(200).json(customer);
    } catch (error: any) {
        res.status(500).json({ error: error.message });
    }
});

