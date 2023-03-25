import express, { Express } from 'express';
import { Sequelize } from 'sequelize-typescript';
import CustomerModel from '../customer/repository/sequilize/customer.model';
import { customerRoute } from './routes/customer.route';


export const app: Express = express();
app.use(express.json());
app.use("/customer", customerRoute)

export let sequilize: Sequelize;

async function setupDb() {
    sequilize = new Sequelize({
        dialect: 'sqlite',
        storage: ':memory:',
        logging: false,
    });
  
    await sequilize.addModels([CustomerModel]);
    await sequilize.sync();
}

setupDb();