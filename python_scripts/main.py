import pandas as pd
import pymysql
from surprise import Dataset
from surprise import Reader
from surprise import KNNBasic
from surprise.model_selection import train_test_split
from surprise.dump import dump
from dotenv import load_dotenv
import os

load_dotenv()

conn = pymysql.connect(
    host=os.getenv('DB_HOST'),
    user=os.getenv('DB_USERNAME'),
    password=os.getenv('DB_PASSWORD'),
    database=os.getenv('DB_DATABASE')
)

cursor = conn.cursor()

query = "SELECT user_id,item_id,rating FROM item_user_ratings"
query1 = "SELECT o.customer_id as user_id,item_id FROM orders o join order_histories oh on o.id=oh.order_id"

ratings_data = pd.read_sql(query, conn)
orders_data = pd.read_sql(query1, conn)

conn.close()

data = pd.concat([ratings_data, orders_data])

reader = Reader(rating_scale=(1, 5))
dataset = Dataset.load_from_df(data[['user_id', 'item_id', 'rating']], reader)

trainset, testset = train_test_split(dataset, test_size=0.2)

model = KNNBasic(k=3, sim_options={'user_based': True})
model.fit(trainset)

dump_file = 'model.dump'
dump(dump_file, algo=model)


