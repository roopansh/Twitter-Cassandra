from cassandra.cluster import Cluster
import glob
import json
import datetime


CLUSTER = ['127.0.0.1']
KEYSPACE = "roopansh"
cluster = Cluster(CLUSTER)
session = cluster.connect()


def checkConnection():
	global session

	# Check if keyspace exist
	try:
		session.set_keyspace(KEYSPACE)
	except Exception as e:
		# Create the keyspace and connect
		print(e)
		session.execute("CREATE KEYSPACE " + KEYSPACE +  " WITH REPLICATION = { 'class' : 'SimpleStrategy', 'replication_factor' : 3 }")
		print("Created keyspace '"+ KEYSPACE + "' successfully")
	finally:
		session.set_keyspace(KEYSPACE)

	print("Connected to the keyspace - 'roopansh'")


def twitter1():
	global session

	try:
		session.execute("TRUNCATE twitter1")
	except Exception as e:
		print(e)
		print('Creating table twitter1')
		session.execute("""
			CREATE TABLE twitter1 (
				tid varint,
				tweet_text text,
				author text,
				location text,
				lang text,
				datetime timestamp,
				primary key ((author), datetime, tid)
			)
			WITH CLUSTERING ORDER BY (datetime DESC)
		""")


def populate():
	global session

	print('Populating dataset')
	insert_stmt_1 = session.prepare("INSERT INTO twitter1 (tid, tweet_text, author, location, lang, datetime) VALUES (?,?,?,?,?,?)")

	# Read & populate dataset
	dataset = glob.glob("./dataset/*.json")
	for file in dataset:
		with open(file, 'r') as f:
			data = json.load(f)

		for tweet in data:
			tid = int(data[tweet]['tid'])
			tweet_text = data[tweet]['tweet_text']
			author = data[tweet]['author']
			location = data[tweet]['location']
			lang = data[tweet]['lang']
			tweet_datetime = datetime.datetime.strptime(data[tweet]['datetime'], "%Y-%m-%d %H:%M:%S")

			try:
				session.execute_async(insert_stmt_1, [tid, tweet_text, author, location, lang, tweet_datetime])
			except Exception as e:
				print(e)
				exit(1)



def main():
	checkConnection()
	twitter1()
	populate()



if __name__ == '__main__':
	main()
