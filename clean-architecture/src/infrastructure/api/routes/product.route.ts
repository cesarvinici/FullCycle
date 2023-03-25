import express, { Request, Response } from 'express';
import CreateProductUseCase from '../../../usecase/product/create/create.product.usercase';
import FindProductUsecase from '../../../usecase/product/find/find.product.usecase';
import ListProductUsecase from '../../../usecase/product/list/list.product.usecases';
import UpdateProductUseCase from '../../../usecase/product/update/update.product.usecase';
import ProductRepository from '../../product/repository/sequilize/product.repository';


export const productRoute = express.Router();

productRoute.post('/', async (req: Request, res: Response) => {
    const useCase = new CreateProductUseCase(new ProductRepository());

    try {
        const productDto = {
            name: req.body.name,
            price: req.body.price,
            description: req.body.description
        }
        const product = await  useCase.execute(productDto);
        res.status(200).json(product);
    } catch (error: any) {
        res.status(500).json({ error: error.message });
    }
});

productRoute.get('/', async (req: Request, res: Response) => {
    const useCase = new ListProductUsecase(new ProductRepository());

    try {
        const products = await useCase.execute({});
        res.status(200).json(products);
    } catch (error: any) {
        res.status(500).json({ error: error.message });
    }
});

productRoute.get('/:id', async (req: Request, res: Response) => {
    const useCase = new FindProductUsecase(new ProductRepository());

    try {
        const products = await useCase.execute({ id: req.params.id });
        res.status(200).json(products);
    } catch (error: any) {
        res.status(500).json({ error: error.message });
    }
});

productRoute.post("/:id", async (req: Request, res: Response) => {
    const useCase = new UpdateProductUseCase(new ProductRepository());

    try {
        const productDto = {
            id: req.params.id,
            name: req.body.name,
            price: req.body.price,
            description: req.body.description
        }
        const product = await useCase.execute(productDto);
        res.status(200).json(product);
    } catch (error: any) {
        res.status(500).json({ error: error.message });
    }
});
