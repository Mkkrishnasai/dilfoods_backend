from surprise.dump import load
import sys
import pymysql
import pandas as pd
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

query = "SELECT iur.user_id,iur.item_id,iur.rating,i.name FROM item_user_ratings iur join items i on i.id=iur.item_id"
query1 = "SELECT o.customer_id,item_id FROM orders o join order_histories oh on o.id=oh.order_id"

data = pd.read_sql(query, conn)
history_data = pd.read_sql(query1, conn)

args = sys.argv[1:]

dump_file = args[0]
_, model = load(dump_file)


user_id = args[1]
user_unrated_items = [item for item in data['user_id'].unique() if item not in data[data['user_id'] == int(user_id)]['item_id']]
predictions = []
boost_factor=0.2
for item in user_unrated_items:
    prediction = model.predict(user_id, item)
    previous_order_items = history_data[history_data['customer_id'] == int(user_id)]['item_id'].tolist()
    if item in previous_order_items:
        boosted_rating = prediction.est + boost_factor
    else:
        boosted_rating = prediction.est

    predictions.append((item, boosted_rating))

predictions.sort(key=lambda x: x[1], reverse=True)

top_recommendations = predictions[:6]
print([item for item, _ in top_recommendations])
# print([item for item, _ in top_recommendations])
