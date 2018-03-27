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
		session.execute("CREATE KEYSPACE " + KEYSPACE +  " WITH REPLICATION = { 'class' : 'SimpleStrategy', 'replication_factor' : 1 }")
		print("Created keyspace '"+ KEYSPACE + "' successfully")
	finally:
		session.set_keyspace(KEYSPACE)

	print("Connected to the keyspace - 'roopansh'")


def twitter1():
	global session

	try:
		session.execute("TRUNCATE twitter1")
	except Exception as e:
		print('Creating table twitter1')
		session.execute("""
			CREATE TABLE twitter1 (
				tid bigint,
				tweet_text text,
				author text,
				location text,
				lang text,
				datetime text,
				primary key (author, datetime, tid)
			) WITH CLUSTERING ORDER BY (datetime DESC, tid ASC)
		""")


def twitter2():
	global session

	try:
		session.execute("TRUNCATE twitter2")
	except Exception as e:
		print('Creating table twitter2')
		session.execute("""
		    CREATE TABLE twitter2 (
            	tid bigint,
            	keyword text,
            	tweet_text text,
            	like_count int,
            	PRIMARY KEY (keyword, like_count, tid)
            ) WITH CLUSTERING ORDER BY (like_count desc, tid asc);
		""")


def twitter3():
	global session

	try:
		session.execute("TRUNCATE twitter3")
	except Exception as e:
		print('Creating table twitter3')
		session.execute("""
			CREATE TABLE twitter3 (
            	hashtag text,
	            tid bigint,
	            tweet_text text,
            	datetime text,
            	PRIMARY KEY (hashtag, datetime, tid)
            ) WITH CLUSTERING ORDER BY (datetime DESC, tid ASC);
		""")


def twitter4():
	global session

	try:
		session.execute("TRUNCATE twitter4")
	except Exception as e:
		print('Creating table twitter4')
		session.execute("""
			CREATE TABLE twitter4 (
            	mention text,
	            tid bigint,
	            tweet_text text,
            	datetime text,
            	PRIMARY KEY (mention, datetime, tid)
            ) WITH CLUSTERING ORDER BY (datetime DESC, tid ASC);
		""")


def twitter5():
	global session

	try:
		session.execute("TRUNCATE twitter5")
	except Exception as e:
		print('Creating table twitter5')
		session.execute("""
			CREATE TABLE twitter5 (
            	date text,
	            tid bigint,
	            tweet_text text,
            	like_count int,
            	PRIMARY KEY (date, like_count, tid)
            ) WITH CLUSTERING ORDER BY (like_count DESC, tid ASC);
		""")


def twitter6():
	global session

	try:
		session.execute("TRUNCATE twitter6")
	except Exception as e:
		print('Creating table twitter6')
		session.execute("""
			CREATE TABLE twitter6 (
	            tid bigint,
	            tweet_text text,
            	location text,
            	PRIMARY KEY (location, tid)
            ) WITH CLUSTERING ORDER BY (tid ASC);
		""")


# def twitter7():
# 	global session

# 	try:
# 		session.execute("TRUNCATE twitter7")
# 	except Exception as e:
# 		print('Creating table twitter7')
# 		session.execute("""
# 			CREATE TABLE twitter7 (
#             	date text,
#             	hashtag text,
#             	tid text,
#             	PRIMARY KEY (date, hashtag, tid)
#             ) WITH CLUSTERING ORDER BY (hashtag ASC, tid ASC);
# 		""")


def populate():
	global session
	print('Populating dataset')

	# Prepare the insertion statements
	insert_stmt_1 = session.prepare("INSERT INTO twitter1 (tid, tweet_text, author, location, lang, datetime) VALUES (?,?,?,?,?,?)")
	insert_stmt_2 = session.prepare("INSERT INTO twitter2 (tid, keyword, tweet_text, like_count) VALUES (?,?,?,?)")
	insert_stmt_3 = session.prepare("INSERT INTO twitter3 (tid, hashtag, tweet_text, datetime) VALUES (?,?,?,?)")
	insert_stmt_4 = session.prepare("INSERT INTO twitter4 (tid, mention, tweet_text, datetime) VALUES (?,?,?,?)")
	insert_stmt_5 = session.prepare("INSERT INTO twitter5 (tid, like_count, tweet_text, date) VALUES (?,?,?,?)")
	insert_stmt_6 = session.prepare("INSERT INTO twitter6 (tid, location, tweet_text) VALUES (?,?,?)")

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
			tweet_datetime = data[tweet]['datetime']
			tweet_date = data[tweet]['date']
			like_count = int(data[tweet]['like_count'])
			keywords = data[tweet]['keywords_processed_list']
			hashtags = data[tweet]['hashtags']
			mentions = data[tweet]['mentions']

			# TABLE 1
			try:
				session.execute_async(insert_stmt_1, [tid, tweet_text, author, location, lang, tweet_datetime])
			except Exception as e:
				print(e)
				exit(1)

			# TABLE 2
			try:
				if keywords is not None:
					for keyword in keywords:
						session.execute_async(insert_stmt_2, [tid, keyword, tweet_text, like_count])
			except Exception as e:
				print(e)
				exit(1)

			# TABLE 3
			try:
				if hashtags is not None:
					for hashtag in hashtags:
						session.execute_async(insert_stmt_3, [tid, hashtag, tweet_text, tweet_datetime])
			except Exception as e:
				print(e)
				exit(1)

			# TABLE 4
			try:
				if mentions is not None:
					for mention in mentions:
						session.execute_async(insert_stmt_4, [tid, mention, tweet_text, tweet_datetime])
			except Exception as e:
				print(e)
				exit(1)

			# TABLE 5
			try:
				session.execute_async(insert_stmt_5, [tid, like_count, tweet_text, tweet_date])
			except Exception as e:
				print(e)
				exit(1)

			# TABLE 6
			try:
				session.execute_async(insert_stmt_6, [tid, location, tweet_text])
			except Exception as e:
				print(e)
				exit(1)

			# # TABLE 7
			# try:
			# 	session.execute_async(insert_stmt_7, [tid, location, tweet_text])
			# except Exception as e:
			# 	print(e)
			# 	exit(1)

	print("successfully populated!")


def main():
	checkConnection()

	twitter1()
	twitter2()
	twitter3()
	twitter4()
	twitter5()
	twitter6()
	# twitter7()

	populate()


if __name__ == '__main__':
	main()
