# Apparel Price
Welcome to the WEC Docker recuitment task. This is a simple web app that displays info about apparel items and their prices. 
Items are stored in a PostgreSQL databse service(db folder). This is queried using a Express.js service(apparel folder) which sends it to the Flask service(prices folder). Here, the items are assigned prices, and sent to the frontend PHP service(site folder).
