#!/usr/bin/env sh

# set our game environment up
# note the word supersedure is used by beekeepers to describe the death and replacement of a queen bee
# in this instance, when a queen dies the rest of the hive will also die
export BEE_QUEEN_LIFESPAN=30
export BEE_QUEEN_DAMAGE=8
export BEE_QUEEN_QUANTITY=1
export BEE_QUEEN_SUPERSEDURE=1

export BEE_WORKER_LIFESPAN=7
export BEE_WORKER_DAMAGE=10
export BEE_WORKER_QUANTITY=1

export BEE_DRONE_LIFESPAN=5
export BEE_DRONE_DAMAGE=12
export BEE_DRONE_QUANTITY=1

# run our game
bin/console bees:play