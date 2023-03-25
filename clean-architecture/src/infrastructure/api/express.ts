import express, { Express } from 'express';
import { Sequelize } from 'sequelize-typescript';
import CustomerModel from '../customer/repository/sequilize/customer.model';
import ProductModel from '../product/repository/sequilize/product.model';
import { customerRoute } from './routes/customer.route';
import { productRoute } from './routes/product.route';


export const app: Express = express();
app.use(express.json());
app.use("/customer", customerRoute)
app.use("/product", productRoute)

export let sequilize: Sequelize;

async function setupDb() {
    sequilize = new Sequelize({
        dialect: 'sqlite',
        storage: ':memory:',
        logging: false,
    });
  
    await sequilize.addModels([CustomerModel, ProductModel]);
    await sequilize.sync();
}

setupDb();